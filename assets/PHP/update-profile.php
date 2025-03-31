<?php
include './connection.php';

$user_id = $_POST['user_id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$query = "UPDATE users SET fullname='$fullname', email='$email', username='$username', password='$password', phone='$phone', address='$address' WHERE user_id=$user_id";

if (mysqli_query($conn, $query)) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}
?>