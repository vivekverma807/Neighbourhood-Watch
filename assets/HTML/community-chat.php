<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Chat | Neighbourhood Watch</title>
    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="stylesheet" href="../CSS/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header id="header">
        <nav class="left">
            <a href="#menu"><span>Menu</span></a>
        </nav>
        <a href="./homepage.php" class="logo">Neighbourhood Watch</a>
        <nav class="right">
            <div class="profile-dropdown">
                <div class="profile-container">
                    <img src="<?php echo htmlspecialchars($_SESSION['user_data']['profile_image'] ?? '../profile-image/default.png'); ?>" 
                         alt="Profile" class="profile-image">
                    <span class="profile-text"><?php echo htmlspecialchars($_SESSION['user_data']['fullname']); ?></span>
                    <i class="fas fa-caret-down" style="color: rgb(0, 0, 0);"></i>
                </div>
                <div class="dropdown-content">
                    <a href="../PHP/user-profile.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="../PHP/edit-profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
                    <a href="./settings.html"><i class="fas fa-cog"></i> Settings</a>
                    <a href="../PHP/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Navigation Menu -->
    <nav id="menu">
        <ul class="links">
            <li><a href="./homepage.php">Home</a></li>
            <li><a href="./map.html">Map</a></li>
            <li><a href="./report-incident.html">Report Incident</a></li>
            <li><a href="./emergency-pin.html">Emergency Pin</a></li>
            <li><a href="./community-chat.php">Community Chat</a></li>
            <li><a href="./about.html">About</a></li>
            <li><a href="./contact.html">Contact Us</a></li>
        </ul>
        <ul class="actions vertical">
            <li><a href="../PHP/logout.php" class="button fit">Logout</a></li>
        </ul>
    </nav>

    <main class="chat-main">
        <section class="chat-section">
            <h2>Community Chat Room</h2>
            <div class="chat-container">
                <div class="online-users">
                    <h3>Online Members</h3>
                    <ul id="online-users-list">
                        <!-- Online users will appear here -->
                    </ul>
                </div>
                <div class="chat-box">
                    <div id="chat-messages" class="chat-messages">
                        <!-- Chat messages will appear here -->
                    </div>
                    <div class="chat-input">
                        <input type="text" id="message-input" placeholder="Type your message here..." autocomplete="off">
                        <button id="send-button"><i class="fas fa-paper-plane"></i> Send</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer">
        <div class="inner">
            <h2>Contact Us</h2>
            <ul class="actions">
                <li><span class="icon fa-phone"></span> <a href="#">(000) 000-0000</a></li>
                <li><span class="icon fa-envelope"></span> <a href="#">information@untitled.tld</a></li>
                <li><span class="icon fa-map-marker"></span> 123 Somewhere Road, Nashville, TN 00000</li>
            </ul>
        </div>
        <div class="copyright">
            Developed by: <a href="https://github.com/Yuvrajkumar1668">Yuvraj Singh</a> & <a href="https://github.com/vivekverma807">Vivek Kumar Verma</a>.
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../JS/jquery.min.js"></script>
    <script src="../JS/jquery.scrolly.min.js"></script>
    <script src="../JS/skel.min.js"></script>
    <script src="../JS/util.js"></script>
    <script src="../JS/main.js"></script>
    <script>
        // Chat functionality
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const onlineUsersList = document.getElementById('online-users-list');
            
            // Current user data from PHP session
            const currentUser = {
                id: '<?php echo $_SESSION['user_id']; ?>',
                name: '<?php echo $_SESSION['user_data']['fullname']; ?>',
                avatar: '<?php echo $_SESSION['user_data']['profile_image']; ?>'
            };

            // Simulate online users (in real app, this would come from server)
            const onlineUsers = [
                { id: currentUser.id, name: currentUser.name, avatar: currentUser.avatar },
                { id: '2', name: 'John Doe', avatar: '../profile-image/default.png' },
                { id: '3', name: 'Jane Smith', avatar: '../profile-image/default.png' }
            ];

            // Display online users
            function displayOnlineUsers() {
                onlineUsersList.innerHTML = '';
                onlineUsers.forEach(user => {
                    const userItem = document.createElement('li');
                    userItem.innerHTML = `
                        <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
                        <span>${user.name}</span>
                    `;
                    onlineUsersList.appendChild(userItem);
                });
            }

            // Display a new message
            function displayMessage(user, message) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                
                messageDiv.innerHTML = `
                    <div class="message-header">
                        <img src="${user.avatar}" alt="${user.name}" class="message-avatar">
                        <span class="message-sender">${user.name}</span>
                        <span class="message-time">${new Date().toLocaleTimeString()}</span>
                    </div>
                    <div class="message-content">${message}</div>
                `;
                
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Send message function
            function sendMessage() {
                const message = messageInput.value.trim();
                if (message) {
                    displayMessage(currentUser, message);
                    messageInput.value = '';
                    
                    // In a real app, you would send the message to the server here
                    // For simulation, we'll add a bot response after 1 second
                    setTimeout(() => {
                        const botUser = { 
                            id: '0', 
                            name: 'Community Bot', 
                            avatar: '../profile-image/bot.png' 
                        };
                        displayMessage(botUser, "Thanks for your message! Our community team will review if needed.");
                    }, 1000);
                }
            }

            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Initialize
            displayOnlineUsers();
            
            // Add welcome message
            const botUser = { 
                id: '0', 
                name: 'Community Bot', 
                avatar: '../profile-image/bot.png' 
            };
            displayMessage(botUser, `Welcome to the community chat, <strong><u>${currentUser.name}</u></strong>! Please keep discussions respectful and relevant to neighborhood safety.`);
        });
    </script>
</body>
</html>