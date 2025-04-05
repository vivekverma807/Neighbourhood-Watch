<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/edit-profile.css">
    <link rel="stylesheet" href="assets/css/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>

    <!-- Profile Picture -->
    <div class="profile-picture">
        <img id="profile-img" src="../profile-image/default.png" alt="Profile Picture">
        <input type="file" id="upload-img" accept="image/*">
    </div>

    <!-- Profile Form -->
    <form id="edit-profile-form">
        <input type="hidden" id="user_id" value="1"> <!-- Static user_id for testing -->
        
        <label>Full Name:</label>
        <input type="text" id="fullname" required>
        
        <label>Email:</label>
        <input type="email" id="email" required>

        <label>Username:</label>
        <input type="text" id="username" maxlength="10" required>

        <label>Password:</label>
        <input type="password" id="password" maxlength="8" required>

        <label>Phone:</label>
        <input type="tel" id="phone" maxlength="15" required>

        <label>Address:</label>
        <textarea id="address" required></textarea>

        <button type="submit">Update Profile</button>
    </form>
</div>

<!-- Modal for Image Cropper -->
<div id="cropper-modal">
    <div class="modal-content">
        <span id="close-modal">&times;</span>
        <img id="cropper-image">
        <button id="crop-btn">Crop & Upload</button>
    </div>
</div>

<script src="assets/js/cropper.min.js"></script>
<script src="../JS/edit-profile.js"></script>
</body>
</html>