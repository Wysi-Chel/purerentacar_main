<?php
include 'php/dbconfig.php';

// Count total gig cars
$count_sql = "SELECT COUNT(*) AS total FROM gigcars";
$count_result = $conn->query($count_sql);
if ($count_result && $row_count = $count_result->fetch_assoc()) {
    $total_gigcars = $row_count['total'];
} else {
    $total_gigcars = 0;
}

// Fetch all gig cars data for the table listing.
$sql = "SELECT 
    g.id,
    g.make,
    g.model,
    g.year,
    g.license_plate,
    g.vin_num,
    g.category,
    g.status,
    g.gigcar_dispimage AS image,
    g.seaters,
    g.num_doors,
    g.runs_on_gas,
    g.mpg,
    g.daily_rate,
    g.weekly_rate
FROM gigcars g";
$result = $conn->query($sql);

// Check if a specific gigcar id is provided via GET for the details section.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $selected_id = intval($_GET['id']);
    $sql_single = "SELECT * FROM gigcars WHERE id = $selected_id";
    $result_single = $conn->query($sql_single);
    if ($result_single->num_rows > 0) {
        $gigcar = $result_single->fetch_assoc();
    } else {
        $gigcar = null;
    }
} else {
    $gigcar = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Gig Cars Dashboard</title>
    <?php include 'head.php';?>
</head>
<body class="dark-scheme">
<div id="wrapper">
    <header class="transparent has-topbar">
        <div id="topbar" class="topbar-dark text-light"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex sm-pt10">
                        <div class="de-flex-col">
                            <div id="logo">
                                <a href="index.php">
                                    <img class="logo-1" src="images/logo-purerental.png" alt="">
                                    <img class="logo-2" src="images/logo-purerental.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="de-flex-col header-col-mid">
                            <ul id="mainmenu">
                                <li><a class="menu-item" href="admin-dashboard.php">Premium Cars</a></li>
                                <li><a class="menu-item" href="gigcars-dashboard.php">Gig Cars</a></li>
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

    <!-- Subheader Section Begin -->
    <?php if ($gigcar): ?>
    <section id="subheader" class="jarallax text-light">
        <img src="images/background/2.jpg" class="jarallax-img" alt="">
        <div class="center-y relative text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1><?php echo $gigcar['make'] . " " . $gigcar['model']; ?></h1>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <!-- Default Subheader when no gigcar is selected -->
    <section id="subheader" class="jarallax text-light">
        <img src="images/background/2.jpg" class="jarallax-img" alt="">
        <div class="center-y relative text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1>Gig Cars Fleet</h1>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Subheader Section Close -->

    <!-- Content Begin -->
    <div id="content" class="no-bottom no-top">
        <section id="gigcars-dashboard" class="container-fluid" style="padding: 120px 50px;">
            <!-- Overview Cards -->
            <div class="row">
                <div class="col-lg-3 mb30">
                    <div class="card p-4 rounded-5">
                        <div class="symbol mb40">
                            <i class="fa fa-car fa-2x"></i>
                        </div>
                        <h5 class="mb0">Total Gig Cars</h5>
                        <span class="h1"><?php echo $total_gigcars; ?></span>
                    </div>
                </div>
            </div>

            <!-- Gig Cars Management Section -->
            <div class="card p-4 rounded-5 mb25">
                <div class="d-flex justify-content-between align-items-center mb20">
                    <h4>Manage Gig Cars</h4>
                    <a href="add_gigcar.php" class="btn-main btn-small">List New Gig Car</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Display Image</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>License Plate</th>
                                <th>VIN#</th>
                                <th>Category</th>
                                <th>MPG</th>
                                <th>Daily Rate</th>
                                <th>Weekly Rate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>";
                                if (!empty($row['image'])) {
                                    echo "<img src='" . $row['image'] . "' width='50' class='car-thumb' alt='" . $row['make'] . " " . $row['model'] . "'>";
                                } else {
                                    echo "No image";
                                }
                                echo "</td>";
                                echo "<td>" . $row['make'] . "</td>";
                                echo "<td>" . $row['model'] . "</td>";
                                echo "<td>" . $row['year'] . "</td>";
                                echo "<td>" . $row['license_plate'] . "</td>";
                                echo "<td>" . $row['vin_num'] . "</td>";
                                echo "<td>" . $row['category'] . "</td>";      
                                echo "<td>" . $row['mpg'] . "</td>";
                                echo "<td>" . (!empty($row['daily_rate']) ? "$" . number_format($row['daily_rate'], 2) : "N/A") . "</td>";
                                echo "<td>" . (!empty($row['weekly_rate']) ? "$" . number_format($row['weekly_rate'], 2) : "N/A") . "</td>";
                                echo "<td>" . (strtolower($row['status']) === 'available' ? "<span class='badge bg-success'>Available</span>" : "<span class='badge bg-danger'>Unavailable</span>") . "</td>";
                                echo "<td>
                                        <button type='button' class='btn-main edit-btn' data-id='" . $row['id'] . "' data-bs-toggle='modal' data-bs-target='#editCarModal'>Edit</button>
                                        <a href='admin-delete-gigcar.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No gig cars found.</td></tr>";
                        }
                        ?>  
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <!-- Content Close -->

    <a href="#" id="back-to-top"></a>
</div>

<!-- Modal for editing gig car details -->
<div class="modal fade" id="editCarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCarModalLabel">Edit Gig Car Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="editCarIframe" src="" style="width:100%; height:600px; border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

<!-- Javascript Files -->
<script src="js/plugins.js"></script>
<script src="js/designesia.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
  // When the editCarModal is shown, update the iframe's src to load the admin-edit-gigcar page for the selected car.
  var editCarModal = document.getElementById('editCarModal');
  editCarModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget; // Button that triggered the modal
      var gigcarId = button.getAttribute('data-id'); // Get the gigcar id from data attribute
      var iframe = document.getElementById('editCarIframe');
      iframe.src = "edit-gigcar.php?id=" + gigcarId;
  });
</script>
</body>
</html>
