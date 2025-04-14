<?php
include '../php/dbconfig.php';

// Escape and assign car details from POST
$make     = $conn->real_escape_string($_POST['make']);
$model    = $conn->real_escape_string($_POST['model']);
$year     = intval($_POST['year']);
$category = $conn->real_escape_string($_POST['category']);
$status   = 'Available';  // Default status

// Define the target directory for images
$targetDir = "../images/cars/";  // Adjust the path as needed
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Process the official (display) image upload
if(isset($_FILES['display_image']) && $_FILES['display_image']['error'] == 0) {
    $displayImageName = uniqid() . "_" . basename($_FILES['display_image']['name']);
    $displayImagePath = $targetDir . $displayImageName;
    if(move_uploaded_file($_FILES['display_image']['tmp_name'], $displayImagePath)) {
        // Optionally, remove "../" if your stored paths are relative to the site root.
        // For example, store "images/cars/filename.jpg" instead of "../images/cars/filename.jpg"
        $displayImageForDB = "images/cars/" . $displayImageName;
    } else {
        die("Error uploading official image.");
    }
} else {
    die("Official image is required.");
}

// Insert the car details along with the official image into the cars table
$sql = "INSERT INTO cars (make, model, year, category, status, display_image) 
        VALUES ('$make', '$model', '$year', '$category', '$status', '$displayImageForDB')";
        
if ($conn->query($sql) === TRUE) {
    // Get the ID of the inserted car record
    $car_id = $conn->insert_id;
    
    // Process Additional Images if provided
    if(isset($_FILES['additional_images'])) {
        foreach($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
            // Skip files with errors or no file selected
            if ($_FILES['additional_images']['error'][$key] != 0) {
                continue;
            }
            $fileName = uniqid() . "_" . basename($_FILES['additional_images']['name'][$key]);
            $targetFile = $targetDir . $fileName;
            if(move_uploaded_file($tmp_name, $targetFile)) {
                // Store the relative path in the database (adjust path as needed)
                $additionalImagePath = "images/cars/" . $fileName;
                $sqlImage = "INSERT INTO car_images (car_id, image_path) VALUES ('$car_id', '$additionalImagePath')";
                $conn->query($sqlImage);
            }
        }
    }
    
    // Process rental rates for 1 to 7 days if required
    for ($day = 1; $day <= 7; $day++) {
        if(isset($_POST["rental_rate_$day"])) {
            $rate = floatval($_POST["rental_rate_$day"]);
            $sqlRate = "INSERT INTO car_rental_rates (car_id, rental_day, rate) VALUES ('$car_id', '$day', '$rate')";
            $conn->query($sqlRate);
        }
    }
    
    echo "Car added successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
