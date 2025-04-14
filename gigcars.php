<?php 
include 'php/dbconfig.php';

// Build the WHERE clause based on GET parameters (adjusted for your schema)
$whereClauses = [];

// Filter by Make (available makes: bmw, ford, jeep)
if (isset($_GET['make']) && !empty($_GET['make'])) {
    $make = $conn->real_escape_string($_GET['make']);
    $whereClauses[] = "g.make = '$make'";
}

// Filter by Year
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = (int) $_GET['year'];
    $whereClauses[] = "g.year = $year";
}

// Filter by Category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    if (strtolower($category) === 'available') {
        // Filter by status instead of category
        $whereClauses[] = "g.status = 'Available'";
    } else {
        $whereClauses[] = "g.category = '$category'";
    }
}

// Filter by Rental Rate (price) using daily_rate from the cars table
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $min_price = (float) $_GET['min_price'];
    $whereClauses[] = "g.daily_rate >= $min_price";
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $max_price = (float) $_GET['max_price'];
    $whereClauses[] = "g.daily_rate <= $max_price";
}

$whereSQL = "";
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$sql = "SELECT g.* FROM gigcars g" . $whereSQL;
$result = $conn->query($sql);

// Fetch distinct makes, years, and categories for the filters
$sql_make = "SELECT DISTINCT make FROM gigcars";
$sql_year = "SELECT DISTINCT year FROM gigcars";
$sql_category = "SELECT DISTINCT category FROM gigcars";

$make_result = mysqli_query($conn, $sql_make);
$year_result = mysqli_query($conn, $sql_year);
$category_result = mysqli_query($conn, $sql_category);
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php';?>
<head>
    <title>Gig Cars - Homepage</title>
    <style>
        /* Ensure that all columns are the same height */
        .de-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        
        /* Style the select and number inputs inside the filter form */
        #filterForm input[type="number"],
        #filterForm select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
        }

        /* Style for the Apply Filters button */
        #filterForm input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        #filterForm input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Ensure proper spacing for the gallery */
        .de-item-list {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        /* Additional styling for the car cards */
        .d-img img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .d-info {
            margin-top: 15px;
        }

        /* Styling for the car details */
        .d-info h4 {
            font-size: 18px;
            font-weight: bold;
        }

        .d-atr-group {
            margin-top: 10px;
        }

        .d-price {
            font-size: 16px;
            color: #3498db;
            margin-top: 10px;
        }

        /* Ensure consistent layout on all devices */
        @media (max-width: 767px) {
            .col-xl-3, .col-lg-4 {
                flex: 1 0 100%; /* Stack columns on small screens */
            }
        }
    </style>
</head>
<body class="light-scheme" onload="initialize()">
    <div id="wrapper">

        <div id="de-preloader"></div>

        <?php include 'header.php' ?>

        <!-- content begin -->
        <div class="no-bottom no-top zebra" id="content">
            <div id="top"></div>

            <!-- section begin -->
            <section id="subheader" class="jarallax text-light">
                <img src="images/background/2.jpg" class="jarallax-img" alt="">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Gig Cars Available for Rent</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- section close -->


            <section id="section-cars">
                <div class="container">
                    <div class="row">
                        <!-- Sidebar Filters -->
                        <div class="col-lg-3">
                            <form method="GET" action="gigcars.php" id="filterForm">
                                <!-- Make Filter -->
                                <div class="item_filter_group">
                                    <h4>Make</h4>
                                    <div class="de_form">
                                        <select name="make">
                                            <option value="">All</option>
                                            <?php
                                            while ($make_row = mysqli_fetch_assoc($make_result)) {
                                                $selected = isset($_GET['make']) && $_GET['make'] === $make_row['make'] ? 'selected' : '';
                                                echo "<option value='{$make_row['make']}' {$selected}>{$make_row['make']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Year Filter -->
                                <div class="item_filter_group">
                                    <h4>Year</h4>
                                    <div class="de_form">
                                        <input type="number" name="year" placeholder="e.g. 2020" value="<?php echo isset($_GET['year']) ? $_GET['year'] : ''; ?>" style="color: #333;">
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="item_filter_group">
                                    <h4>Category</h4>
                                    <div class="de_form">
                                        <select name="category">
                                            <option value="">All</option>
                                            <option value="available" <?php if (isset($_GET['category']) && $_GET['category'] === 'available') echo 'selected'; ?>>Available</option>
                                            <option value="Sport" <?php if (isset($_GET['category']) && $_GET['category'] === 'Sport') echo 'selected'; ?>>Sport</option>
                                            <option value="Pickup" <?php if (isset($_GET['category']) && $_GET['category'] === 'Pickup') echo 'selected'; ?>>Pickup</option>
                                            <option value="SUV" <?php if (isset($_GET['category']) && $_GET['category'] === 'SUV') echo 'selected'; ?>>SUV</option>
                                        </select>
                                    </div>
                                </div>

                                <a href="gigcars.php" class="btn" style="display:inline-block; margin-top:10px; margin-bottom:10px; background-color:#ccc; color:#333; padding:10px 20px; border-radius:4px; text-decoration:none;">Reset Filters</a>
                                <input type="submit" value="Apply Filters">
                            </form>
                        </div>

                        <!-- Car Listings -->
                        <div class="col-lg-9">
                            <div class="row">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $gigcar_id = $row['id'];
                                        $make = $row['make'];
                                        $model = $row['model'];
                                        $year = $row['year'];
                                        $seater = $row['seaters'];
                                        $num_doors = $row['num_doors'];
                                        $category = $row['category'];
                                        $status = $row['status'];
                                        $image = $row['gigcar_dispimage'];
                                        $daily_rate = $row['daily_rate'];
                                        $weekly_rate = $row['weekly_rate'];
                                ?>
                                        <div class="col-xl-4 col-lg-6">
                                            <div class="de-item mb30">
                                                <div class="d-img">
                                                    <img src="<?php echo $image ? $image : 'images/default-car.jpg'; ?>" class="img-fluid" alt="Gigcar Image">
                                                </div>
                                                <div class="d-info" > 
                                                    <div class="d-text" style="margin-top: -50px;">
                                                        <h4> <?php echo $make . " " . $model; ?> </h4> <h6><?php echo $year ?> </h6> 
                                                        <div class="d-atr-group" style="margin-top: -10px;">
                                                            <span class="d-atr"><img src="images/icons/1-green.svg" alt=""><?php echo $seater; ?></span>
                                                            <span class="d-atr"><img src="images/icons/3-green.svg" alt=""><?php echo $num_doors; ?></span>
                                                            <span class="d-atr"><img src="images/icons/4-green.svg" alt=""><?php echo $category; ?></span>
                                                        </div>
                                                        <div class="d-price">
                                                            Daily rate from <span>$<?php echo number_format($daily_rate, 2); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "<p>No gig cars available.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <!-- content close -->

            <a href="#" id="back-to-top"></a>

            <?php include 'footer.php' ?>

        </div>
        <script src="js/plugins.js"></script>
        <script src="js/designesia.js"></script>

    </body>
</html>
