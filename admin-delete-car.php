<?php
// admin-delete-car.php

include 'php/dbconfig.php';

// Validate car id from GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}

$car_id = intval($_GET['id']);

// Retrieve the car's display image path (if any)
$sqlSelect = "SELECT display_image FROM cars WHERE id = $car_id";
$resultSelect = $conn->query($sqlSelect);

if ($resultSelect && $resultSelect->num_rows > 0) {
    $row = $resultSelect->fetch_assoc();
    $displayImage = $row['display_image'];
    
    // Delete the display image file if it exists (adjust path if necessary)
    if (!empty($displayImage) && file_exists($displayImage)) {
        unlink($displayImage);
    }
}

// Delete the car record from the cars table
$sqlDelete = "DELETE FROM cars WHERE id = $car_id";
if (!$conn->query($sqlDelete)) {
    die("Error deleting car: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Car Deleted</title>
  <!-- Bootstrap 5 CSS via CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Success</h5>
        </div>
        <div class="modal-body">
          Car deleted successfully!
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper via CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize and show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    // Redirect after 2 seconds (adjust the path as necessary)
    setTimeout(function(){
      window.location.href = 'admin-dashboard.php';
    }, 2000);
  </script>
</body>
</html>
