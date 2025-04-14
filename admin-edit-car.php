<?php
include 'php/dbconfig.php';

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No car ID provided.");
}
$car_id = intval($_GET['id']);

// Fetch car details from the cars table
$sql = "SELECT * FROM cars WHERE id = $car_id";
$carResult = $conn->query($sql);
if ($carResult->num_rows == 0) {
    die("Car not found.");
}
$car = $carResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Car Details</title>
    <?php include 'head.php'; ?>
    <style>
        .current-img {
            max-width: 200px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="light-scheme">
    <div class="container">
        <form action="process_edit_car.php" method="post" enctype="multipart/form-data">
            <!-- Hidden input for car id -->
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">

            <div class="mb-3">
                <label for="make" class="form-label">Make</label>
                <input type="text" class="form-control" id="make" name="make" value="<?php echo htmlspecialchars($car['make']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($car['year']); ?>" required>
            </div>
            <!-- Category: dynamically generated select -->
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <?php
                    // Fetch distinct categories from the cars table
                    $cat_sql = "SELECT DISTINCT category FROM cars WHERE category IS NOT NULL AND TRIM(category) <> ''";
                    $cat_result = $conn->query($cat_sql);
                    if ($cat_result && $cat_result->num_rows > 0) {
                        while ($cat_row = $cat_result->fetch_assoc()) {
                            $selected = ($cat_row['category'] == $car['category']) ? "selected" : "";
                            echo "<option value=\"" . htmlspecialchars($cat_row['category']) . "\" $selected>" . htmlspecialchars($cat_row['category']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No categories available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Available" <?php echo (strtolower($car['status']) === 'available' || $car['status'] == '1') ? "selected" : ""; ?>>Available</option>
                    <option value="Unavailable" <?php echo (strtolower($car['status']) !== 'available' && $car['status'] != '1') ? "selected" : ""; ?>>Unavailable</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="seaters" class="form-label">Seater</label>
                <input type="text" class="form-control" id="seaters" name="seaters" value="<?php echo htmlspecialchars($car['seaters']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="num_doors" class="form-label">Doors</label>
                <input type="text" class="form-control" id="num_doors" name="num_doors" value="<?php echo htmlspecialchars($car['num_doors']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="runs_on_gas" class="form-label">Powered</label>
                <select name="runs_on_gas" id="runs_on_gas" class="form-select" required>
                    <option value="battery" <?php echo (trim(strtolower($car['runs_on_gas'])) === 'battery' ? 'selected' : ''); ?>>Battery</option>
                    <option value="gas (regular)" <?php echo (trim(strtolower($car['runs_on_gas'])) === 'gas (regular)' ? 'selected' : ''); ?>>Gas (Regular)</option>
                    <option value="gas (premium)" <?php echo (trim(strtolower($car['runs_on_gas'])) === 'gas (premium)' ? 'selected' : ''); ?>>Gas (Premium)</option>
                    <option value="hybrid (regular)" <?php echo (trim(strtolower($car['runs_on_gas'])) === 'hybrid (regular)' ? 'selected' : ''); ?>>Hybrid (Regular)</option>
                    <option value="hybrid (premium)" <?php echo (trim(strtolower($car['runs_on_gas'])) === 'hybrid (premium)' ? 'selected' : ''); ?>>Hybrid (Premium)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="mpg" class="form-label">MPG</label>
                <input type="text" class="form-control" id="mpg" name="mpg" value="<?php echo htmlspecialchars($car['mpg']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Official (Display) Image</label><br>
                <?php if (!empty($car['display_image'])): ?>
                    <img src="<?php echo $car['display_image']; ?>" alt="Current Display Image" class="current-img">
                <?php else: ?>
                    <p>No official image uploaded.</p>
                <?php endif; ?>
                <input type="file" class="form-control" name="display_image">
                <small class="form-text text-muted">Upload a new image to replace the current official image.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Additional Images</label>
                <input type="file" class="form-control" name="additional_images[]" multiple>
                <small class="form-text text-muted">You can select multiple additional images.</small>
            </div>

            <hr>
            <h4>Rental Rates</h4>
            <p>Daily Rate:
                <input type="number" step="0.01" class="form-control" name="daily_rate" value="<?php echo htmlspecialchars($car['daily_rate']); ?>">
            </p>
            <p>Weekly Rate:
                <input type="number" step="0.01" class="form-control" name="weekly_rate" value="<?php echo htmlspecialchars($car['weekly_rate']); ?>">
            </p>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <!-- Cancel button calls cancelEdit() -->
            <button type="button" class="btn btn-secondary" onclick="cancelEdit();">Cancel</button>
        </form>
    </div>

    <!-- JavaScript Files -->
    <script src="js/plugins.js"></script>
    <script src="js/designesia.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // This function closes the parent modal when Cancel is clicked.
        function cancelEdit() {
            // Use the parent's bootstrap object to get the modal instance
            var modalEl = window.parent.document.getElementById('editCarModal');
            var modalInstance = window.parent.bootstrap.Modal.getInstance(modalEl);
            if (!modalInstance) {
                modalInstance = new window.parent.bootstrap.Modal(modalEl);
            }
            modalInstance.hide();
        }
    </script>
</body>

</html>