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

    <!-- Banner Section -->
    <section id="banner">
        <div class="content">
            <h1>Neighbourhood Watch</h1>
            <p>Your eyes and ears for community safety. Together, we're building<br>safer neighborhoods through vigilance and technology.</p>
            <ul class="actions">
                <li><a href="#one" class="button scrolly">Explore Features</a></li>
            </ul>
        </div>
    </section>

    <!-- Section One -->
    <section id="one" class="wrapper">
        <div class="inner flex flex-3">
            <div class="flex-item left">
                <div>
                    <h3>Real-Time Alerts</h3>
                    <p>Get instant notifications about safety concerns<br>and incidents in your immediate area.</p>
                </div>
                <div>
                    <h3>Community Network</h3>
                    <p>Connect with verified neighbors to share<br>information and coordinate safety efforts.</p>
                </div>
            </div>
            <div class="flex-item image fit round">
                <img src="../images/neighborhood-watch-team.jpg" alt="Community members discussing safety" width="330" height="330">
            </div>
            <div class="flex-item right">
                <div>
                    <h3>Incident Reporting</h3>
                    <p>Quickly report suspicious activities<br>with our easy-to-use forms.</p>
                </div>
                <div>
                    <h3>Safety Resources</h3>
                    <p>Access guides, checklists, and tips<br>to enhance your home security.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Two -->
    <section id="two" class="wrapper style1 special">
        <div class="inner">
            <h2>Community Testimonials</h2>
            <div class="testimonials-container">
                <figure>
                    <blockquote>
                        "Since joining Neighbourhood Watch, our community has seen a 40% reduction in petty crimes. The real-time alerts are invaluable!"
                    </blockquote>
                    <footer>
                        <img src="../images/testimonial1.jpg" alt="Michael R." class="testimonial-avatar">
                        <cite class="author">Michael R.</cite>
                        <cite class="company">Oakwood Resident, 3 years</cite>
                    </footer>
                </figure>
                <figure>
                    <blockquote>
                        "The emergency pin feature helped us quickly alert neighbors when we spotted a break-in attempt. Police response was faster thanks to the app."
                    </blockquote>
                    <footer>
                        <img src="../images/testimonial2.jpg" alt="Sarah K." class="testimonial-avatar">
                        <cite class="author">Sarah K.</cite>
                        <cite class="company">Maple Street Block Captain</cite>
                    </footer>
                </figure>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="wrapper style2">
        <div class="inner">
            <h2 class="align-center">Our Impact in Numbers</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">15,000+</div>
                    <div class="stat-label">Active Members</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">62%</div>
                    <div class="stat-label">Reduction in Break-ins</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Monitoring</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">85%</div>
                    <div class="stat-label">Faster Emergency Response</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Three -->
    <section id="three" class="wrapper">
        <div class="inner flex flex-3">
            <div class="flex-item box">
                <div class="image fit">
                    <img src="../images/community-meeting.jpg" alt="Community safety meeting" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Neighborhood Groups</h3>
                    <p>Join or create localized groups to discuss specific safety concerns and organize patrols.</p>
                    <a href="#" class="button small">Find Your Group</a>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <img src="../images/safety-workshop.jpg" alt="Safety workshop" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Safety Workshops</h3>
                    <p>Learn crime prevention techniques and emergency preparedness from local experts.</p>
                    <a href="#" class="button small">View Schedule</a>
                </div>
            </div>
            <div class="flex-item box">
                <div class="image fit">
                    <img src="../images/police-partnership.jpg" alt="Police community partnership" width="418" height="200">
                </div>
                <div class="content">
                    <h3>Police Partnerships</h3>
                    <p>Direct line to local law enforcement with verified incident reporting.</p>
                    <a href="#" class="button small">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="wrapper style1">
        <div class="inner">
            <h2 class="align-center">How Neighbourhood Watch Works</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Sign Up & Verify</h3>
                    <p>Register with your address and complete our verification process to join your local network.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Connect With Neighbors</h3>
                    <p>Access your neighborhood's private communication channels and safety resources.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Stay Informed & Report</h3>
                    <p>Receive alerts and contribute by reporting incidents or suspicious activities.</p>
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