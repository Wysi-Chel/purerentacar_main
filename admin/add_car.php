<?php
include '../php/dbconfig.php';

// Initialize variables for messages
$error = "";
$success = "";

// Process form submission if POSTed to this page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect car details from form inputs
    $make        = trim($_POST['make']);
    $model       = trim($_POST['model']);
    $year        = intval($_POST['year']);
    $category    = trim($_POST['category']);
    
    // New car details
    $seaters     = intval($_POST['seaters']);
    $num_doors   = intval($_POST['num_doors']);
    $runs_on_gas = trim($_POST['runs_on_gas']); // expecting one of the predefined options
    $mpg         = floatval($_POST['mpg']);
    
    // Rental rates (Daily and Weekly)
    $daily_rate  = floatval($_POST['daily_rate']);
    $weekly_rate = floatval($_POST['weekly_rate']);
    
    // Set status as Available by default.
    $status      = "Available";

    // Process the uploaded display image
    if (isset($_FILES['display_image']) && $_FILES['display_image']['error'] == 0) {
        $targetDir = "images/cars/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename   = uniqid() . "_" . basename($_FILES['display_image']['name']);
        $targetFile = $targetDir . $filename;
        if (move_uploaded_file($_FILES['display_image']['tmp_name'], $targetFile)) {
            $display_image_path = "images/cars/" . $filename;
        } else {
            die("Error uploading new display image.");
        }
    }
    if (empty($error)) {
        // Insert the main car record including new fields (daily_rate, weekly_rate)
        $stmt = $conn->prepare("INSERT INTO cars (make, model, year, category, status, display_image, seaters, num_doors, runs_on_gas, mpg, daily_rate, weekly_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssiisddd", $make, $model, $year, $category, $status, $uploadFile, $seaters, $num_doors, $runs_on_gas, $mpg, $daily_rate, $weekly_rate);
        
        if ($stmt->execute()) {
            // Get the inserted car's ID
            $car_id = $stmt->insert_id;
            $stmt->close();
    
            // Process additional images only after the main insert (when $car_id is available)
            if (isset($_FILES['additional_images']) && $_FILES['additional_images']['error'][0] === 0) {
                for ($i = 0; $i < count($_FILES['additional_images']['name']); $i++) {
                    $uploadDir = "images/cars/";
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $filename = uniqid() . '_' . basename($_FILES['additional_images']['name'][$i]);
                    $targetFile = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $targetFile)) {
                        // Insert the image record into the car_images table
                        $stmt_img = $conn->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
                        $stmt_img->bind_param("is", $car_id, $targetFile);
                        $stmt_img->execute();
                        $stmt_img->close();
                    }
                }
            }
            $success = "New car added successfully!";
        } else {
            $error = "Error adding car: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="../images/rel-icon.png" type="image/gif" sizes="32x32">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pure Rental Group Webpage">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- CSS Files -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="../css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="../css/plugins.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <link href="../css/coloring.css" rel="stylesheet" type="text/css">
    <!-- Color scheme -->
    <link id="colors" href="../css/colors/scheme-07.css" rel="stylesheet" type="text/css">
    <style>
        /* Dark-scheme settings */
        body.dark-scheme {
            background-color: #1e1e2d;
            color: #c7c7c7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Header styling */
        header.transparent {
            background: rgba(0, 0, 0, 0.7);
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        header .menu-item {
            color: #fff;
            margin: 0 10px;
            transition: color 0.3s;
        }
        header .menu-item:hover {
            color: #3498db;
        }
        header .btn-main {
            background-color: #3498db;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        header .btn-main:hover {
            background-color: #2980b9;
        }
        /* Container for the add car form */
        .add-car-container {
            background: rgba(46, 46, 62, 0.9);
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            margin: 80px auto;
            max-width: 800px;
        }
        .add-car-container h1 {
            color: #fff;
            margin-bottom: 30px;
            text-align: center;
        }
        /* Form group and input styling */
        .add-car-container .form-group label {
            color: #ddd;
            font-weight: 500;
        }
        .add-car-container .form-control,
        .add-car-container .form-control-file {
            background: #444;
            border: 1px solid #555;
            color: #fff;
        }
        .add-car-container .form-control:focus {
            background: #555;
            border-color: #3498db;
            box-shadow: none;
        }
        /* Button styling */
        .add-car-container .btn-main {
            background-color: #3498db;
            color: #fff;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-car-container .btn-main:hover {
            background-color: #2980b9;
        }
        /* Alert styling */
        .add-car-container .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body onload="initialize()" class="dark-scheme">
    <div id="wrapper">
        <div id="de-preloader"></div>

        <!-- Header Begin -->
        <header class="transparent scroll-light has-topbar">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <!-- Logo Begin -->
                        <div id="logo">
                            <a href="../index.php">
                                <img class="logo-1" src="../images/logo-purerental.png" alt="">
                                <img class="logo-2" src="../images/logo-purerental.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Main Content -->
        <div class="container add-car-container" style="margin-top: 150px;">
            <h1>Add New Car</h1>

            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <?php if (!empty($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php } ?>

            <!-- Form with file upload support -->
            <form action="" method="post" enctype="multipart/form-data">
                <!-- Car Basic Details -->
                <div class="form-group">
                    <label for="make">Make:</label>
                    <input type="text" name="make" id="make" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" name="model" id="model" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="number" name="year" id="year" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="">Select a Category</option>
                        <option value="sedan">Sport</option>
                        <option value="suv">SUV</option>
                        <option value="truck">Sedan</option>
                        <option value="coupe">Van</option>
                        <option value="coupe">Pickup</option>
                    </select>
                </div>
                <!-- New Car Details -->
                <div class="form-group">
                    <label for="seaters">Seaters:</label>
                    <input type="number" name="seaters" id="seaters" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="num_doors">Doors:</label>
                    <input type="number" name="num_doors" id="num_doors" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="runs_on_gas">Powered by:</label>
                    <select name="runs_on_gas" id="runs_on_gas" class="form-control" required>
                        <option value="">Select an Option</option>
                        <option value="battery">Battery</option>
                        <option value="gas_regular">Gas (Regular)</option>
                        <option value="gas_premium">Gas (Premium)</option>
                        <option value="hybrid_regular">Hybrid (Regular)</option>
                        <option value="hybrid_premium">Hybrid (Premium)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mpg">Miles Per Gallon (MPG):</label>
                    <input type="number" name="mpg" id="mpg" class="form-control">
                </div>
                
                <!-- Rental Rates for Daily and Weekly -->
                <div class="form-group">
                    <label for="daily_rate">Daily Rate:</label>
                    <input type="number" step="0.01" name="daily_rate" id="daily_rate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="weekly_rate">Weekly Rate:</label>
                    <input type="number" step="0.01" name="weekly_rate" id="weekly_rate" class="form-control">
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn-main">Add Car</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript Files -->
    <script src="../js/plugins.js"></script>
    <script src="../js/designesia.js"></script>
</body>
</html>
