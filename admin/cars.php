<?php

// Include the database configuration
include '../php/dbconfig.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Dashboard</title>
    <link rel="icon" href="images/rel-icon.png" type="image/gif" sizes="32x32">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pure Rental Group Admin Dashboard">
    <!-- CSS Files -->  
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="css/mdb.min.css" rel="stylesheet" type="text/css" id="mdb">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/coloring.css" rel="stylesheet" type="text/css">
    <link id="colors" href="css/colors/scheme-07.css" rel="stylesheet" type="text/css">
</head>
<body onload="initialize()" class="dark-scheme">
<div id="wrapper">
    <!-- Header Section -->
    <header class="transparent has-topbar">
        <div id="topbar" class="topbar-dark text-light"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex sm-pt10">
                        <div class="de-flex-col">
                            <div id="logo">
                                <a href="index.html">
                                    <img class="logo-1" src="images/logo-purerental.png" alt="">
                                    <img class="logo-2" src="images/logo-purerental.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="de-flex-col header-col-mid">
                            <ul id="mainmenu">
                                <li><a class="menu-item" href="dashboard.php">Dashboard</a></li>
                                <li><a class="menu-item" href="cars.php">Cars</a></li>
                                <li><a class="menu-item" href="admin-employees.php">Employees</a></li>
                                <li><a class="menu-item" href="admin-users.php">Users</a></li>
                            </ul>
                        </div>
                        <div class="de-flex-col">
                            <div class="mainmenu">
                                <a href="logout.php" class="btn-main">Logout</a>
                                <span id="menu-btn"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Close -->

    <!-- Content Begin -->
    <div id="content" class="no-bottom no-top">
        <div id="top"></div>
        <section id="admin-dashboard" class="container-fluid" style="padding: 120px 50px;">
            <!-- Overview Cards (unchanged) -->
            <div class="row">
                <div class="col-lg-3 mb30">
                    <div class="card p-4 rounded-5">
                        <div class="symbol mb40">
                            <i class="fa fa-car fa-2x"></i>
                        </div>
                        <h5 class="mb0">Total Cars</h5>
                        <span class="h1">0</span>
                    </div>
                </div>
                <!-- Additional overview cards... -->
            </div>

            <!-- Cars Management Section -->
            <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Cars</h4>
                    <a href="add_car.php" class="btn-main btn-small">Add New Car</a>
                </div>
                <table class="table de-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Category</th>
                            <th>Rental Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch car data from the "cars" table
                        $sql = "SELECT * FROM cars";
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['make'] . "</td>";
                                echo "<td>" . $row['model'] . "</td>";
                                echo "<td>" . $row['year'] . "</td>";
                                echo "<td>" . $row['category'] . "</td>";
                                echo "<td>$" . number_format($row['rental_rate'], 2) . "</td>";
                                
                                // Check the status and display an appropriate badge
                                if (strtolower($row['status']) === 'available' || $row['status'] == 1) {
                                    echo "<td><span class='badge bg-success'>Available</span></td>";
                                } else {
                                    echo "<td><span class='badge bg-danger'>Unavailable</span></td>";
                                }
                                
                                echo "<td>
                                        <a href='admin-edit-car.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Edit</a>
                                        <a href='admin-delete-car.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No cars found.</td></tr>";
                        }
                        
                        // Close the connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Employees Management Section (unchanged) -->
            <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Employees</h4>
                    <a href="admin-add-employee.php" class="btn-main btn-small">Add New Employee</a>
                </div>
                <table class="table de-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Employee rows here -->
                    </tbody>
                </table>
            </div>

            <!-- Users Management Section (unchanged) -->
            <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Users</h4>
                </div>
                <table class="table de-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- User rows here -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <!-- Content Close -->

    <a href="#" id="back-to-top"></a>
    <!-- Footer Section (unchanged) -->
    <footer class="text-light">
        <div class="container">
            <div class="row g-custom-x">
                <!-- Footer widgets -->
            </div>
        </div>
        <div class="subfooter">
            <div class="container">
                <!-- Footer subcontent -->
            </div>
        </div>
    </footer>
</div>

<!-- Javascript Files -->
<script src="js/plugins.js"></script>
<script src="js/designesia.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&amp;libraries=places&amp;callback=initPlaces" async defer></script>
</body>
</html>
