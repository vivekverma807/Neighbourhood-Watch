<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if (isset($_FILES['profile_image'])) {
    $uploadDir = '../profile-image/';
    $fileName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.jpg';
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
        echo json_encode(['success' => true, 'filePath' => $targetFile]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
}
?>