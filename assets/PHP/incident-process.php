<?php
header('Content-Type: application/json');

// Database configuration - UPDATE THESE WITH YOUR CREDENTIALS
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "neighbourhood_watch";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false, 
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Validate required fields
$required_fields = ['title', 'category', 'description', 'date', 'time', 'address'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = "All required fields must be filled out.";
        echo json_encode($response);
        exit;
    }
}

// Process form data with validation
$title = $conn->real_escape_string(trim($_POST['title']));
$type = $conn->real_escape_string(trim($_POST['category']));
$description = $conn->real_escape_string(trim($_POST['description']));
$address = $conn->real_escape_string(trim($_POST['address']));
$contact_info = isset($_POST['contact']) ? $conn->real_escape_string(trim($_POST['contact'])) : '';

// Parse date and time into proper format
$incident_date = date('Y-m-d', strtotime($_POST['date']));
$incident_time = date('H:i:s', strtotime($_POST['time']));

// Parse latitude and longitude if provided
$latitude = $longitude = null;
if (!empty($_POST['location'])) {
    $location = $conn->real_escape_string(trim($_POST['location']));
    $coords = explode(',', $location);
    if (count($coords) === 2) {
        $latitude = floatval(trim($coords[0]));
        $longitude = floatval(trim($coords[1]));
    }
}

// First insert the incident data to get the ID
$sql = "INSERT INTO incidents (
    title, 
    type, 
    description, 
    address, 
    latitude, 
    longitude, 
    contact_info,
    incident_date,
    incident_time
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $response['message'] = "Prepare failed: " . $conn->error;
    echo json_encode($response);
    exit;
}

$stmt->bind_param(
    "ssssdssss", 
    $title, 
    $type, 
    $description, 
    $address, 
    $latitude, 
    $longitude, 
    $contact_info,
    $incident_date,
    $incident_time
);

if (!$stmt->execute()) {
    $response['message'] = "Error: " . $stmt->error;
    $stmt->close();
    $conn->close();
    echo json_encode($response);
    exit;
}

$newIncidentId = $stmt->insert_id;
$stmt->close();

// Handle file upload after we have the incident ID
$evidence_path = '';
if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $fileName = basename($_FILES["evidence"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    if (in_array($fileExt, $allowedExts)) {
        if ($_FILES["evidence"]["size"] <= $maxFileSize) {
            // Create filename using incident ID
            $newFileName = 'incident_' . $newIncidentId . '.' . $fileExt;
            $targetFile = $targetDir . $newFileName;
            
            if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $targetFile)) {
                $evidence_path = $targetFile;
                
                // Update the record with the evidence path
                $updateSql = "UPDATE incidents SET evidence_path = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $evidence_path, $newIncidentId);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                $response['message'] = "Error uploading file.";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = "File size exceeds 5MB limit.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['message'] = "Invalid file type. Only images, videos, and PDFs are allowed.";
        echo json_encode($response);
        exit;
    }
}

$response = [
    'success' => true,
    'incident_id' => $newIncidentId,
    'message' => 'Incident reported successfully!'
];

$conn->close();
echo json_encode($response);
?>