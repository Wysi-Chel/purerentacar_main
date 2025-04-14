<?php
include '../dbconfig.php';  // Contains your MySQL connection code

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $user_id = intval($_POST['user_id']);
    $car_id = intval($_POST['car_id']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate dates (this example assumes proper date format "YYYY-MM-DD")
    if (strtotime($start_date) === false || strtotime($end_date) === false) {
        die("Invalid dates provided.");
    }
    if (strtotime($start_date) > strtotime($end_date)) {
        die("Start date must be before or equal to end date.");
    }
    if (strtotime($start_date) < strtotime(date("Y-m-d"))) {
        die("Start date cannot be in the past.");
    }

    // Check for overlapping bookings
    $sql_overlap = "SELECT COUNT(*) as count FROM bookings 
                    WHERE car_id = ? 
                      AND ((? BETWEEN start_date AND end_date) OR (? BETWEEN start_date AND end_date) OR (start_date BETWEEN ? AND ?))";
    $stmt = $conn->prepare($sql_overlap);
    $stmt->bind_param("issss", $car_id, $start_date, $end_date, $start_date, $end_date);
    $stmt->execute();
    $result_overlap = $stmt->get_result();
    $row = $result_overlap->fetch_assoc();
    $stmt->close();

    if ($row['count'] > 0) {
        die("Car is already booked for the selected period.");
    }

    // Insert booking
    $sql_insert = "INSERT INTO bookings (user_id, car_id, start_date, end_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iiss", $user_id, $car_id, $start_date, $end_date);
    if ($stmt->execute()) {
        echo "Booking created successfully!";
    } else {
        echo "Error creating booking: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
