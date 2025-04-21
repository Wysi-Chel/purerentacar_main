<?php
// process_edit_gigcar.php

include 'php/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $car_id      = intval($_POST['car_id']);
    $make        = $conn->real_escape_string($_POST['make']);
    $model       = $conn->real_escape_string($_POST['model']);
    $year        = intval($_POST['year']);
    $license_plate = $conn->real_escape_string($_POST['license_plate']);
    $vin_num       = $conn->real_escape_string($_POST['vin_num']);
    $category    = $conn->real_escape_string($_POST['category']);
    $status      = $conn->real_escape_string($_POST['status']);
    
    $seaters     = intval($_POST['seaters']);
    $num_doors   = intval($_POST['num_doors']);
    $runs_on_gas = $conn->real_escape_string($_POST['runs_on_gas']);
    $mpg         = floatval($_POST['mpg']);
    
    $daily_rate  = floatval($_POST['daily_rate']);
    $weekly_rate = floatval($_POST['weekly_rate']);
    
    // Initialize variable for new display image path if uploaded
    $display_image_path = "";
    
    // Process display image upload if a new file is provided
    if (isset($_FILES['gigcar_dispimage']) && $_FILES['gigcar_dispimage']['error'] == 0) {
        $targetDir = "images/cars/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename   = uniqid() . "_" . basename($_FILES['gigcar_dispimage']['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES['gigcar_dispimage']['tmp_name'], $targetFile)) {
            $display_image_path = "images/cars/" . $filename;
        } else {
            die("Error uploading new display image.");
        }
    }

    // Check if the car_id exists in the gigcars table
    $sql_check_car = "SELECT id FROM gigcars WHERE id = ?";
    $stmt_check_car = $conn->prepare($sql_check_car);
    $stmt_check_car->bind_param("i", $car_id);
    $stmt_check_car->execute();
    $stmt_check_car->store_result();

    if ($stmt_check_car->num_rows > 0) {
        // Car exists, proceed with updating the gigcar
        if (!empty($display_image_path)) {
            $updateSQL = "UPDATE gigcars SET 
                            make = '$make', 
                            model = '$model', 
                            year = '$year', 
                            license_plate = '$license_plate',
                            vin_num = '$vin_num',
                            category = '$category', 
                            status = '$status', 
                            seaters = $seaters,
                            num_doors = $num_doors,
                            runs_on_gas = '$runs_on_gas',
                            mpg = $mpg,
                            daily_rate = $daily_rate,
                            weekly_rate = $weekly_rate,
                            gigcar_dispimage = '$display_image_path'
                          WHERE id = $car_id";
        } else {
            $updateSQL = "UPDATE gigcars SET 
                            make = '$make', 
                            model = '$model', 
                            year = '$year', 
                            license_plate = '$license_plate',
                            vin_num = '$vin_num',
                            category = '$category', 
                            status = '$status',
                            seaters = $seaters,
                            num_doors = $num_doors,
                            runs_on_gas = '$runs_on_gas',
                            mpg = $mpg,
                            daily_rate = $daily_rate,
                            weekly_rate = $weekly_rate
                          WHERE id = $car_id";
        }

        if (!$conn->query($updateSQL)) {
            die("Error updating gigcar: " . $conn->error);
        }

        // Process additional images upload if provided
        if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'][0] === 0) {
            // Loop through each uploaded additional image
            for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
                $uploadDir = "images/cars/"; // Ensure this directory exists and is writable
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = uniqid() . '_' . basename($_FILES['additional_images']['name'][$i]);
                $targetFile = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $targetFile)) {
                    // Check if car_id exists in car_images before inserting new record
                    $sql_check_image = "SELECT car_id FROM car_images WHERE car_id = ?";
                    $stmt_check_image = $conn->prepare($sql_check_image);
                    $stmt_check_image->bind_param("i", $car_id);
                    $stmt_check_image->execute();
                    $stmt_check_image->store_result();
                    
                    if ($stmt_check_image->num_rows > 0) {
                        // Insert the image record into the car_images table
                        $stmt_img = $conn->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
                        $stmt_img->bind_param("is", $car_id, $targetFile);
                        $stmt_img->execute();
                        $stmt_img->close();
                    } else {
                        die("Error: Car ID does not exist in car_images table.");
                    }
                }
            }
        }

        $conn->close();

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Success</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-3">
                <div class="alert alert-success">
                    Gig Car updated successfully!
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Close the parent modal and reload the parent window after 2 seconds.
                var modalEl = window.parent.document.getElementById('editCarModal');
                var modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
                modalInstance.hide();
                setTimeout(function(){
                    window.parent.location.reload();
                }, 2000);
            </script>
        </body>
        </html>
        <?php
        exit();
    } else {
        die("Error: Gig Car ID does not exist.");
    }
} else {
    die("Invalid request method.");
}
?>
