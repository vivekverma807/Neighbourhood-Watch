<?php
session_start();
require_once './connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Initialize variables
$error = '';
$success = '';
$userData = [];

// Get current user data
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Process profile image upload if exists
        $profileImage = $userData['profile_image'] ?? '../profile-image/default.png';
        
        if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../profile-image/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Get file extension
            $fileExt = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($fileExt, $allowedExt)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }
            
            // Generate new filename: user_userid.ext
            $fileName = 'user_' . $_SESSION['user_id'] . '.' . $fileExt;
            $targetFile = $uploadDir . $fileName;
            
            // Check if image file is valid
            $check = getimagesize($_FILES['profile_image']['tmp_name']);
            if ($check === false) {
                throw new Exception("File is not an image.");
            }
            
            // Delete old profile image if it's not the default
            if ($profileImage != '../profile-image/default.png' && file_exists($profileImage)) {
                unlink($profileImage);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                $profileImage = $targetFile;
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        }
        
        // Get form data
        $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
        $email = $userData['email']; // Keep original email (read-only)
        $username = $userData['username']; // Keep original username (read-only)
        $date_of_birth = filter_input(INPUT_POST, 'date_of_birth');
        $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
        $password = !empty($_POST['password']) ? $_POST['password'] : null;
        
        // Update query
        $query = "UPDATE users SET 
                  fullname = ?, 
                  date_of_birth = ?, 
                  phone_number = ?, 
                  city = ?, 
                  state = ?, 
                  profile_image = ?,
                  password = IFNULL(?, password) 
                  WHERE user_id = ?";
        
        $stmt = $conn->prepare($query);
        
        // Bind parameters
        $stmt->bind_param("sssssssi", 
            $fullname, 
            $date_of_birth, 
            $phone_number, 
            $city, 
            $state, 
            $profileImage,
            $password,
            $_SESSION['user_id']
        );
        
        if ($stmt->execute()) {
            $_SESSION['user_data'] = [
                'user_id' => $_SESSION['user_id'],
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'date_of_birth' => $date_of_birth,
                'phone_number' => $phone_number,
                'city' => $city,
                'state' => $state,
                'profile_image' => $profileImage
            ];
            $success = "Profile updated successfully!";
        } else {
            throw new Exception("Error updating profile: " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/edit-profile.css">
    <style>
        .error { color: red; }
        .success { color: green; }
        .profile-picture {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 100px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        button {
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- Profile Picture -->
        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($userData['profile_image'] ?? '../profile-image/default.png'); ?>" 
                 alt="Profile Picture"
                 onerror="this.onerror=null; this.src='../profile-image/default.png'">
            <input type="file" name="profile_image" accept="image/*">
        </div>

        <!-- Profile Form -->
        <form method="POST" enctype="multipart/form-data">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" readonly>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>" maxlength="12" readonly>

            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($userData['date_of_birth'] ?? ''); ?>" required>

            <label>Phone Number:</label>
            <input type="tel" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>" maxlength="10" required>

            <label>City:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($userData['city'] ?? ''); ?>" required>

            <label>State:</label>
            <input type="text" name="state" value="<?php echo htmlspecialchars($userData['state'] ?? ''); ?>" required>

            <label>Password (leave blank to keep current):</label>
            <input type="password" name="password" placeholder="New password" maxlength="8">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>