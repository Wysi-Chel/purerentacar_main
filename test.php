<?php 
include 'php/dbconfig.php';

// Build the WHERE clause based on GET parameters
$whereClauses = [];

// Filter by Make (available makes from gigcars)
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
    $whereClauses[] = "g.category = '$category'";
}

// Filter by Rental Rate (price) using daily_rate from the gigcars table
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $min_price = (float) $_GET['min_price'];
    $whereClauses[] = "g.daily_rate >= $min_price";
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $max_price = (float) $_GET['max_price'];
    $whereClauses[] = "g.daily_rate <= $max_price";
}

// Build the WHERE clause for the SQL query
$whereSQL = "";
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

// Fetch filtered gigcars data for the gallery
$sql = "SELECT g.* FROM gigcars g" . $whereSQL;
$result = mysqli_query($conn, $sql);

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
<head>
    <title>Gig Cars - Homepage</title>
</head>
<?php include 'head.php'; ?>

<body class="dark-scheme">
    <div id="wrapper">
        
        <div id="de-preloader"></div>

        <?php include 'header.php'?>

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
                                    <h1>Gig Cars</h1>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- section close -->

            <!-- Filter Form Section -->
            <section id="filter-section">
                <div class="container">
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
                                <select name="year">
                                    <option value="">All</option>
                                    <?php
                                    while ($year_row = mysqli_fetch_assoc($year_result)) {
                                        $selected = isset($_GET['year']) && $_GET['year'] == $year_row['year'] ? 'selected' : '';
                                        echo "<option value='{$year_row['year']}' {$selected}>{$year_row['year']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="item_filter_group">
                            <h4>Category</h4>
                            <div class="de_form">
                                <select name="category">
                                    <option value="">All</option>
                                    <?php
                                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                                        $selected = isset($_GET['category']) && $_GET['category'] === $category_row['category'] ? 'selected' : '';
                                        echo "<option value='{$category_row['category']}' {$selected}>{$category_row['category']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Price Filter (Rental Rate) -->
                        <div class="item_filter_group">
                            <h4>Price ($)</h4>
                            <div class="price-input">
                                <div class="field">
                                    <span>Min</span>
                                    <input type="number" name="min_price" class="input-min" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : 0; ?>" style="color:#333">
                                </div>
                                <div class="field">
                                    <span>Max</span>
                                    <input type="number" name="max_price" class="input-max" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : 1000; ?>" style="color: #333;">
                                </div>
                            </div>
                        </div>
                        <a href="gigcars.php" class="btn" style="display:inline-block; margin-top:10px; margin-bottom:10px; background-color:#ccc; color:#333; padding:10px 20px; border-radius:4px; text-decoration:none;">Reset Filters</a>
                        <input type="submit" value="Apply Filters">
                    </form>
                </div>
            </section>

            <!-- Gig Car Listings -->
            <section id="section-cars">
                <div class="container">
                    <div class="row">
                        <!-- Start Dynamic Gig Cars Gallery -->
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            // Loop through all the gigcars and display them
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Extract the car details
                                $gigcar_id = $row['id'];
                                $make = $row['make'];
                                $model = $row['model'];
                                $year = $row['year'];
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
                                        <div class="d-info">
                                            <div class="d-text">
                                                <h4><?php echo $make . " " . $model; ?> <?php echo $year; ?></h4>
                                                <div class="d-atr-group">
                                                    <span class="d-atr"><img src="images/icons/1-green.svg" alt="">5</span>
                                                    <span class="d-atr"><img src="images/icons/2-green.svg" alt="">2</span>
                                                    <span class="d-atr"><img src="images/icons/3-green.svg" alt="">4</span>
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
            </section>
            
        </div>
        <!-- content close -->

        <a href="#" id="back-to-top"></a>
        
        <?php include 'footer.php'?>
        
    </div>
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>

</body>
</html>
