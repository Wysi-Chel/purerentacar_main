<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';
include 'php/dbconfig.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $first_name = isset($_POST['first_name']) ? strip_tags(trim($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? strip_tags(trim($_POST['last_name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? strip_tags(trim($_POST['phone'])) : '';
    $pickupDate = isset($_POST['PickUpDate']) ? trim($_POST['PickUpDate']) : '';
    $pickup_time = isset($_POST['pickup_time']) ? trim($_POST['pickup_time']) : '';
    $returnDate = isset($_POST['ReturnDate']) ? trim($_POST['ReturnDate']) : '';
    $return_time = isset($_POST['return_time']) ? trim($_POST['return_time']) : '';

    if (!$car_id || !$user_id || empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($pickupDate) || empty($pickup_time) || empty($returnDate) || empty($return_time)) {
        echo 'Please fill in all required booking information.';
        exit;
    }

    $sql_check_conflict = "SELECT COUNT(*) AS conflict_count FROM bookings WHERE car_id = ? AND ((? BETWEEN start_date AND end_date) OR (? BETWEEN start_date AND end_date)) AND status IN ('pending', 'confirmed', 'completed')";
    $stmt = $conn->prepare($sql_check_conflict);
    $stmt->bind_param("iss", $car_id, $pickupDate, $returnDate);
    $stmt->execute();
    $stmt->bind_result($conflict_count);
    $stmt->fetch();
    $stmt->close();

    if ($conflict_count > 0) {
        echo 'The car is already booked for the selected dates.';
        exit;
    }

    $sql_check_customer = "SELECT id FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql_check_customer);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($customer_id);
        $stmt->fetch();
    } else {
        $sql_insert_customer = "INSERT INTO customers (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert_customer);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
        $stmt->execute();
        $customer_id = $stmt->insert_id;
    }

    $sql_car_details = "SELECT make, model FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql_car_details);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->bind_result($car_make, $car_model);
    $stmt->fetch();
    $stmt->close();

    $sql_car_rate = "SELECT daily_rate FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql_car_rate);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->bind_result($rental_rate);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare($sql_car_details);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->bind_result($car_make, $car_model);
    $stmt->fetch();
    $stmt->close();

    $sql_car_rate = "SELECT daily_rate FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql_car_rate);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->bind_result($rental_rate);
    $stmt->fetch();
    $stmt->close();

    $num_days = (strtotime($returnDate) - strtotime($pickupDate)) / (60 * 60 * 24) + 1;
    $total_cost = $rental_rate * $num_days;

    $upload_dir = 'uploads/';
    $drivers_license_path = '';
    $insurance_card_path = '';

    function uploadFile($file, $upload_dir) {
        $target_file = $upload_dir . basename($file["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $valid_extensions = array("jpg", "jpeg", "png", "pdf", "gif");
        if (!in_array($fileType, $valid_extensions)) {
            echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($file["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            return false;
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
    }

    if (isset($_FILES['drivers_license_image'])) {
        $drivers_license_path = uploadFile($_FILES['drivers_license_image'], $upload_dir);
        if (!$drivers_license_path) {
            exit;
        }
    }

    if (isset($_FILES['insurance_card_image'])) {
        $insurance_card_path = uploadFile($_FILES['insurance_card_image'], $upload_dir);
        if (!$insurance_card_path) {
            exit;
        }
    }

    $sql_insert_booking = "INSERT INTO bookings (customer_id, car_id, booking_date, start_date, end_date, total_cost, status, drivers_license_image, insurance_card_image) VALUES (?, ?, CURDATE(), ?, ?, ?, 'confirmed', ?, ?)";
    $stmt = $conn->prepare($sql_insert_booking);
    $stmt->bind_param("iissdss", $user_id, $car_id, $pickupDate, $returnDate, $total_cost, $drivers_license_path, $insurance_card_path);
    $stmt->execute();
    $stmt->close();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'iam.chel1021@gmail.com';
        $mail->Password = 'npon jezx baiw jboe';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('iam.chel1021@gmail.com', 'Chel');
        $mail->addAddress('chellegds@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'New Car Booking Request';

        $emailBody = "<h2>New Booking Request</h2>";
        $emailBody .= "<p><strong>Car Make:</strong> " . htmlspecialchars($car_make) . "</p>";
        $emailBody .= "<p><strong>Car Model:</strong> " . htmlspecialchars($car_model) . "</p>";
        $emailBody .= "<p><strong>Customer Name:</strong> " . htmlspecialchars($first_name) . " " . htmlspecialchars($last_name) . "</p>";
        $emailBody .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        $emailBody .= "<p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>";
        $emailBody .= "<p><strong>Pick Up Date:</strong> " . htmlspecialchars($pickupDate) . "</p>";
        $emailBody .= "<p><strong>Pick Up Time:</strong> " . htmlspecialchars($pickup_time) . "</p>";
        $emailBody .= "<p><strong>Return Date:</strong> " . htmlspecialchars($returnDate) . "</p>";
        $emailBody .= "<p><strong>Return Time:</strong> " . htmlspecialchars($return_time) . "</p>";
        $emailBody .= "<p><strong>Total Cost:</strong> $" . number_format($total_cost, 2) . "</p>";

        $mail->Body = $emailBody;

        if (!empty($drivers_license_path)) {
            $mail->addAttachment($drivers_license_path, 'drivers_license.jpg');
        }
        if (!empty($insurance_card_path)) {
            $mail->addAttachment($insurance_card_path, 'insurance_card.jpg');
        }

        $mail->send();

        header("Location: car-single.php?id=" . $car_id . "&booking=success");
        exit;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo 'Invalid request method.';
}
?>