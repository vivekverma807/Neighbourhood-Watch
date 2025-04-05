<?php
session_start();
$conn = new mysqli("localhost", "root", "", "neighbourhood_watch");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_data'] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'email' => $user['email'],
                'date_of_birth' => $user['date_of_birth'],
                'phone_number' => $user['phone_number'],
                'city' => $user['city'],
                'state' => $user['state'],
                'profile_image' => $user['profile_image']
            ];
            
            header("Location: ../HTML/homepage.php");
            exit;
        } else {
            echo "<script>alert('Invalid Credentials!'); window.location.href='../HTML/login.html';</script>";
        }
    } else {
        echo "<script>alert('Username and Password are required!'); window.location.href='../HTML/login.html';</script>";
    }
}
$conn->close();
?>