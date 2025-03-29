<?php
session_start();
$conn = new mysqli("localhost", "root", "", "neighbourhood_watch");

// ✅ Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);  // ⚠️ Plain text (not secure)
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // ✅ Check if username or email already exists
    $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.location.href='signup.html';</script>";
        exit;
    }

    // ✅ Insert user into users table
    $sql = "INSERT INTO users (fullname, email, username, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullname, $email, $username, $password, $phone, $address);

    if ($stmt->execute()) {
        // ✅ Get the last inserted user_id
        $user_id = $conn->insert_id;

        // ✅ Insert into login table
        $sql_login = "INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)";
        $stmt_login = $conn->prepare($sql_login);
        $stmt_login->bind_param("iss", $user_id, $username, $password);

        if ($stmt_login->execute()) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Error inserting into login table!'); window.location.href='signup.html';</script>";
        }
    } else {
        echo "<script>alert('Error inserting into users table!'); window.location.href='signup.html';</script>";
    }

    // ✅ Close statements & connection
    $stmt->close();
    $stmt_login->close();
    $conn->close();
}
?>