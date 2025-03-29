<?php
session_start();
$conn = new mysqli("localhost", "root", "", "neighbourhood");

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $_SESSION['user'] = $username;
    header("Location: homepage.html");
} else {
    echo "Invalid Credentials!";
}
?>