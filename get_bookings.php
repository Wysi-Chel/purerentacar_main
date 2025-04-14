<?php
include 'php/dbconfig.php';

if (!isset($_GET['car_id']) || empty($_GET['car_id'])) {
    die("No car ID provided.");
}

$car_id = intval($_GET['car_id']);

// Fetch the bookings for the given car
$sql = "SELECT * FROM bookings WHERE car_id = $car_id";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get booking dates and format them into full ISO format
        $start_date = date('Y-m-d', strtotime($row['start_date']));  // Ensure the date format matches the calendar's date format
        $end_date = date('Y-m-d', strtotime($row['end_date']));  // Ensure the date format matches the calendar's date format

        // Add events for the booking period
        $events[] = [
            'start' => $start_date,
            'end' => $end_date,
        ];
    }
}

// Return the events as a JSON object
echo json_encode($events);
?>
