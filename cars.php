<?php
// Include the database configuration file
include 'php/dbconfig.php';

// Build the WHERE clause based on GET parameters (adjusted for your schema)
$whereClauses = [];

// Filter by Make (available makes: bmw, ford, jeep)
if (isset($_GET['make']) && !empty($_GET['make'])) {
    $make = $conn->real_escape_string($_GET['make']);
    $whereClauses[] = "c.make = '$make'";
}

// Filter by Year
if (isset($_GET['year']) && !empty($_GET['year'])) {
    $year = (int) $_GET['year'];
    $whereClauses[] = "c.year = $year";
}

// Filter by Category
if (isset($_GET['category']) && !empty($_GET['category'])) {
  $category = $conn->real_escape_string($_GET['category']);
  if (strtolower($category) === 'available') {
      // Filter by status instead of category
      $whereClauses[] = "c.status = 'Available'";
  } else {
      $whereClauses[] = "c.category = '$category'";
  }
}

// Filter by Rental Rate (price) using daily_rate from the cars table
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $min_price = (float) $_GET['min_price'];
    $whereClauses[] = "c.daily_rate >= $min_price";
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $max_price = (float) $_GET['max_price'];
    $whereClauses[] = "c.daily_rate <= $max_price";
}

$whereSQL = "";
if (count($whereClauses) > 0) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$sql = "SELECT c.* FROM cars c" . $whereSQL;
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Car Fleet</title>
  <?php include 'head.php'; ?>
  <!-- Custom styles for filter form inputs and buttons -->
  <style>
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
  </style>
</head>
<body onload="initialize()" class="light-scheme">
  <div id="wrapper">
    <div id="de-preloader"></div>
    <?php include 'header.php'; ?>

    <!-- Content Begin -->
    <div class="no-bottom no-top zebra" id="content">
      <div id="top"></div>
      <!-- Subheader Section -->
      <section id="subheader" class="jarallax text-light">
        <img src="images/background/2.jpg" class="jarallax-img" alt="">
        <div class="center-y relative text-center">
          <div class="container">
            <div class="row">
              <div class="col-md-12 text-center">
                <h1>Cars Available for Rent</h1>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Cars -->
      <section id="section-cars">
        <div class="container">
          <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
              <form method="GET" action="cars.php" id="filterForm">
                <!-- Make Filter -->
                <div class="item_filter_group">
                  <h4>Make</h4>
                  <div class="de_form">
                    <select name="make">
                      <option value="">All</option>
                      <option value="bmw" <?php if(isset($_GET['make']) && $_GET['make'] === 'bmw') echo 'selected'; ?>>BMW</option>
                      <option value="tesla" <?php if(isset($_GET['make']) && $_GET['make'] === 'tesla') echo 'selected'; ?>>Tesla</option>
                      <option value="lotus" <?php if(isset($_GET['make']) && $_GET['make'] === 'lotus') echo 'selected'; ?>>Lotus</option>
                      <option value="ford" <?php if(isset($_GET['make']) && $_GET['make'] === 'ford') echo 'selected'; ?>>Ford</option>
                      <option value="jeep" <?php if(isset($_GET['make']) && $_GET['make'] === 'jeep') echo 'selected'; ?>>Jeep</option>
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
                      <option value="available" <?php if(isset($_GET['category']) && $_GET['category'] === 'available') echo 'selected'; ?>>Available</option>
                      <option value="Sport" <?php if(isset($_GET['category']) && $_GET['category'] === 'Sport') echo 'selected'; ?>>Sport</option>
                      <option value="Pickup" <?php if(isset($_GET['category']) && $_GET['category'] === 'pickup') echo 'selected'; ?>>Pickup</option>
                      <option value="SUV" <?php if(isset($_GET['category']) && $_GET['category'] === 'SUV') echo 'selected'; ?>>SUV</option>
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
                <a href="cars.php" class="btn" style="display:inline-block; margin-top:10px; margin-bottom:10px; background-color:#ccc; color:#333; padding:10px 20px; border-radius:4px; text-decoration:none;">Reset Filters</a>
                <input type="submit" value="Apply Filters">
              </form>
            </div>

            <!-- Car Listings -->
            <div class="col-lg-9">
              <div class="row">
                <?php
                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-12">
                      <div class="de-item-list mb30">
                        <div class="d-img">
                          <?php if (!empty($row['display_image'])): ?>
                            <img src="<?php echo $row['display_image']; ?>" alt="<?php echo $row['make'] . ' ' . $row['model']; ?>" class="img-fluid">
                          <?php else: ?>
                            <img src="images/default-car.jpg" alt="No image available" class="img-fluid">
                          <?php endif; ?>
                        </div>
                        <div class="d-info">
                          <div class="d-text">
                            <h4><?php echo $row['make'] . " " . $row['model']; ?></h4>
                            <p>Year: <?php echo $row['year']; ?> | Category: <?php echo $row['category']; ?></p>
                          </div>
                        </div>
                        <div class="d-price">
                          Daily rate from <span>$<?php echo (!empty($row['daily_rate']) ? number_format($row['daily_rate'], 2) : "N/A"); ?></span>
                          <a class="btn-main" href="car-single.php?id=<?php echo $row['id']; ?>">Rent Now</a>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                    <?php
                  }
                } else {
                  echo "<p>No cars available.</p>";
                }
                $conn->close();
                ?>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- Content Close -->
    <a href="#" id="back-to-top"></a>
    <?php include 'footer.php'; ?>
  </div>

  <!-- Javascript Files -->
  <script src="js/plugins.js"></script>
  <script src="js/designesia.js"></script>
  <script>
    // Auto-submit the form when a filter changes
    document.querySelectorAll('#filterForm input, #filterForm select').forEach(function(input) {
      input.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
      });
    });
  </script>
</body>
</html>
