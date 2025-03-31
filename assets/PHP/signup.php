<?php
session_start();
$conn = new mysqli("localhost", "root", "", "neighbourhood_watch");

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get all form fields
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $date_of_birth = trim($_POST['dob']);
    $phone_number = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);  // Note: Still plain text (you should hash this)

    // Check if username or email already exists
    $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.location.href='../HTML/sign-up.html';</script>";
        exit;
    }

    // Insert user into users table
    $sql = "INSERT INTO users (fullname, email, date_of_birth, phone_number, city, state, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $fullname, $email, $date_of_birth, $phone_number, $city, $state, $username, $password);

    if ($stmt->execute()) {
        // Get the last inserted user_id
        $user_id = $conn->insert_id;

        // Insert into login table (assuming this table exists)
        $sql_login = "INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)";
        $stmt_login = $conn->prepare($sql_login);
        $stmt_login->bind_param("iss", $user_id, $username, $password);

        if ($stmt_login->execute()) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='../HTML/login.html';</script>";
        } else {
            echo "<script>alert('Error inserting into login table!'); window.location.href='../HTML/sign-up.html';</script>";
        }
    } else {
        echo "<script>alert('Error inserting into users table!'); window.location.href='../HTML/sign-up.html';</script>";
    }

    // Close statements & connection
    $stmt->close();
    if (isset($stmt_login)) {
        $stmt_login->close();
    }
    $conn->close();
}
?>