<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homepage | Neighbourhood Watch</title>
    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="stylesheet" href="../CSS/homepage.css">
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
                    <i class="fas fa-caret-down"></i>
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

    <!-- Rest of your HTML content remains the same -->

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
            <li><a href="./assets/PHP/logout.php" class="button fit">Logout</a></li>
        </ul>
    </nav>

    <!-- Banner Section -->
    <section id="banner">
        <div class="content">
            <h1>Neighbourhood Watch</h1>
            <p>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod<br>sed arcu cras consequat lorem ipsum dolor sit amet.</p>
            <ul class="actions">
                <li><a href="#one" class="button scrolly">Get Started</a></li>
            </ul>
        </div>
    </section>

    <!-- Section One -->
    <section id="one" class="wrapper">
        <div class="inner flex flex-3">
            <div class="flex-item left">
                <div>
                    <h3>Great Features</h3>
                    <p>We provide top-notch services for your neighborhood<br> security and awareness.</p>
                </div>
                <div>
                    <h3>Stay Informed</h3>
                    <p>Get the latest updates and alerts<br> about your community.</p>
                </div>
            </div>
            <div class="flex-item image fit round">
                <img src="./assets/images/pic01.jpg" alt="" width="330" height="330">
            </div>
            <div class="flex-item right">
                <div>
                    <h3>Timely Alerts</h3>
                    <p>Receive alerts and important information<br> about incidents in your area.</p>
                </div>
                <div>
                    <h3>Safe Community</h3>
                    <p>Work together with neighbors to improve<br> security and safety.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Two -->
    <section id="two" class="wrapper style1 special">
        <div class="inner">
            <h2>What People Say</h2>
            <figure>
                <blockquote>
                    "This platform has helped our neighborhood stay informed and alert.<br> We feel much safer knowing what’s happening around us."
                </blockquote>
                <footer>
                    <cite class="author">Jane Anderson</cite>
                    <cite class="company">CEO, Untitled</cite>
                </footer>
            </figure>
        </div>
    </section>

    <!-- Section Three -->
    <section id="three" class="wrapper">
        <div class="inner flex flex-3">
            <div class="flex-item box">
                <div class="image fit">
                    <img src="./assets/images/pic02.jpg" alt="" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Community Support</h3>
                    <p>Get involved and help build a stronger, safer neighborhood.</p>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <img src="./assets/images/pic03.jpg" alt="" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Secure Environment</h3>
                    <p>Join hands with your community members to create a safe and secure locality.</p>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <img src="./assets/images/pic04.jpg" alt="" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Active Participation</h3>
                    <p>Be a part of the change. Report incidents and stay vigilant.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer">
        <div class="inner">
            <h2>Contact Us</h2>
            <ul class="actions">
                <li><span class="icon fa-phone"></span> <a href="#">(000) 000-0000</a></li>
                <li><span class="icon fa-envelope"></span> <a href="#">information@untitled.tld</a></li>
                <li><span class="icon fa-map-marker"></span> 123 Somewhere Road, Nashville, TN 00000</li>
            </ul>
        </div>
    </footer>

    <!-- Copyright -->
    <div class="copyright">
        Developed by: <a href="https://github.com/Yuvrajkumar1668">Yuvraj Singh</a> & <a href="https://github.com/vivekverma807">Vivek Kumar Verma</a>.
    </div>
    
	<!-- Scripts -->
	<script src="../JS/jquery.min.js"></script>
	<script src="../JS/jquery.scrolly.min.js"></script>
	<script src="../JS/skel.min.js"></script>
	<script src="../JS/util.js"></script>
	<script src="../JS/main.js"></script>

    <script>
    // JavaScript to handle dynamic updates
    document.addEventListener('DOMContentLoaded', function() {
        // Update welcome message
        document.getElementById('welcome-message').textContent = 
            `Welcome, <?php echo htmlspecialchars($_SESSION['user_data']['fullname']); ?>!`;
        
        // Update profile image in dashboard
        const dashboardImg = document.getElementById('user-profile-image');
        dashboardImg.src = "<?php echo htmlspecialchars($_SESSION['user_data']['profile_image'] ?? '../images/pic01.jpg'); ?>";
        dashboardImg.alt = "<?php echo htmlspecialchars($_SESSION['user_data']['fullname']); ?>'s profile";
        
        // Load additional data via AJAX
        fetch('../PHP/get-dashboard-data.php')
            .then(response => response.json())
            .then(data => {
                // Update dashboard widgets
                if (data.recent_activity) {
                    document.getElementById('recent-activity').innerHTML = data.recent_activity;
                }
                if (data.neighborhood_alerts) {
                    document.getElementById('neighborhood-alerts').innerHTML = data.neighborhood_alerts;
                }
                if (data.upcoming_events) {
                    document.getElementById('upcoming-events').innerHTML = data.upcoming_events;
                }
                if (data.safety_tips) {
                    document.getElementById('safety-tips').innerHTML = data.safety_tips;
                }
            });
    });
    </script>
</body>
</html>