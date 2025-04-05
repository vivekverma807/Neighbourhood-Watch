<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli("localhost", "root", "", "neighbourhood_watch");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$sql = "SELECT 
            id,
            title, 
            type, 
            description, 
            address,
            latitude as lat, 
            longitude as lng, 
            evidence_path,
            contact_info,
            DATE_FORMAT(incident_date, '%M %e, %Y') as date,
            TIME_FORMAT(incident_time, '%h:%i %p') as time
        FROM incidents 
        WHERE latitude IS NOT NULL 
        AND longitude IS NOT NULL
        ORDER BY incident_date DESC";

$result = $conn->query($sql);
$incidents = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convert evidence path to URL if it exists
        if (!empty($row['evidence_path'])) {
            $row['evidence_url'] = str_replace('../uploads/', '../uploads/', $row['evidence_path']);
        }
        $incidents[] = $row;
    }
}

echo json_encode($incidents);
$conn->close();
?>