<?php
session_start();
$conn = new mysqli("localhost", "root", "", "neighbourhood_watch");

// ✅ Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    if (!empty($username) && !empty($password)) {
        // ✅ Prepare SQL statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // ✅ Check if user exists
        if ($result->num_rows == 1) {
            $_SESSION['user'] = $username;
            header("Location: ../HTML/homepage.html");
            exit;
        } else {
            echo "<script>alert('Invalid Credentials!'); window.location.href='../../index.html';</script>";
        }
    } else {
        echo "<script>alert('Username and Password are required!'); window.location.href='../../index.html';</script>";
    }
}

$conn->close();
?>
