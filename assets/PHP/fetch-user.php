<?php
include './connection.php';
session_start();
header("Access-Control-Allow-Origin: your-domain.com");
header("Access-Control-Allow-Credentials: true");

$user_id = $_GET['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

echo json_encode($user);
?>