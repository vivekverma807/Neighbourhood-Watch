<?php
header('Content-Type: application/json');

// Connect to database
$conn = new mysqli("localhost", "username", "password", "neighbourhood_watch");

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Query to get incidents
$sql = "SELECT * FROM incidents ORDER BY timestamp DESC";
$result = $conn->query($sql);

$incidents = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $incidents[] = $row;
    }
}

echo json_encode($incidents);
$conn->close();
?>