<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

require_once 'connection.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['fullname']); ?>'s Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 5px solid #2c3e50;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .info-value {
            color: #34495e;
        }
        @media (max-width: 768px) {
            .profile-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['profile_image'] ?? '../profile-image/default.png'); ?>" 
                 alt="Profile Image" class="profile-image"
                 onerror="this.src='../profile-image/default.png'">
            <h1><?php echo htmlspecialchars($user['fullname']); ?></h1>
            <p>@<?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        
        <div class="profile-info">
            <div>
                <div class="info-group">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Date of Birth:</div>
                    <div class="info-value"><?php echo date('F j, Y', strtotime($user['date_of_birth'])); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Phone Number:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['phone_number']); ?></div>
                </div>
            </div>
            <div>
                <div class="info-group">
                    <div class="info-label">Location:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['city'] . ', ' . $user['state']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Member Since:</div>
                    <div class="info-value"><?php echo date('F Y', strtotime($user['registration_date'] ?? 'now')); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>