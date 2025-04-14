<?php
// dbconfig.php

// Database connection settings – update these with your actual credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "purerentaldb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
