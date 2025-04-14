<?php
// image.php
include 'dbconfig.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Query for the image blob and type
    $sql = "SELECT image_blob, image_type FROM cars WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Set the content-type header to the image's MIME type
        header("Content-Type: " . $row['image_type']);
        echo $row['image_blob'];
        exit;
    } else {
        echo "Image not found.";
    }
} else {
    echo "No image specified.";
}
?>
