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

    // Handle file upload
    $profile_image_path = "";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../profile-image/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_ext), $allowed_ext)) {
            // Temporarily insert user to get the user_id
            $sql = "INSERT INTO users (fullname, email, date_of_birth, phone_number, city, state, username, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $fullname, $email, $date_of_birth, $phone_number, $city, $state, $username, $password);

            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                $new_filename = "user_" . $user_id . "." . $file_ext;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                    // Update the user record with the profile image path
                    $update_sql = "UPDATE users SET profile_image = ? WHERE user_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $profile_image_path = $destination;
                    $update_stmt->bind_param("si", $profile_image_path, $user_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                } else {
                    echo "<script>alert('Error uploading profile image!'); window.location.href='../HTML/sign-up.html';</script>";
                    exit;
                }

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
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'); window.location.href='../HTML/sign-up.html';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error uploading profile image!'); window.location.href='../HTML/sign-up.html';</script>";
        exit;
    }

    // Close statements & connection
    $stmt->close();
    if (isset($stmt_login)) {
        $stmt_login->close();
    }
    $conn->close();
}
?>