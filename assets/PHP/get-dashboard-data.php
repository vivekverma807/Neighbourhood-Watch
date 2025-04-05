<?php
session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Authentication required']));
}

// Rest of your API code here
?>