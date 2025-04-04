<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "username", "password", "neighbourhood_watch");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Process form data with validation
$title = $conn->real_escape_string(trim($_POST['title']));
$type = $conn->real_escape_string(trim($_POST['category'])); // Changed from 'category' to 'type'
$description = $conn->real_escape_string(trim($_POST['description']));
$address = $conn->real_escape_string(trim($_POST['address']));
$contact_info = $conn->real_escape_string(trim($_POST['contact'])); // Changed from 'contact' to 'contact_info'

// Parse date and time into proper format
$incident_date = date('Y-m-d', strtotime($conn->real_escape_string($_POST['date'])));
$incident_time = date('H:i:s', strtotime($conn->real_escape_string($_POST['time'])));

// Parse latitude and longitude if provided
$latitude = $longitude = null;
$location = $conn->real_escape_string(trim($_POST['location']));
if (!empty($location)) {
    $coords = explode(',', $location);
    if (count($coords) === 2) {
        $latitude = floatval(trim($coords[0]));
        $longitude = floatval(trim($coords[1]));
    }
}

// Handle file upload
$evidence_path = '';
if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["evidence"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'pdf'];
    
    if (in_array($fileExt, $allowedExts)) {
        $newFileName = uniqid() . '_' . $fileName;
        $targetFile = $targetDir . $newFileName;
        
        if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $targetFile)) {
            $evidence_path = $targetFile;
        }
    }
}

// Insert into database using prepared statement
$sql = "INSERT INTO incidents (
    title, 
    type, 
    description, 
    address, 
    latitude, 
    longitude, 
    evidence_path, 
    contact_info,
    incident_date,
    incident_time,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'reported')";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param(
    "ssssddssss", 
    $title, 
    $type, 
    $description, 
    $address, 
    $latitude, 
    $longitude, 
    $evidence_path, 
    $contact_info,
    $incident_date,
    $incident_time
);

if ($stmt->execute()) {
    $newIncidentId = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'incident_id' => $newIncidentId,
        'message' => 'Incident reported successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>