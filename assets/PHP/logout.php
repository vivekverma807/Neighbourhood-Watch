<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Destroy the session completely
session_destroy();

// Prevent caching of protected pages
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// Redirect to login page with cache-busting parameter
header("Location: ../../index.html?logout=" . time());
exit;
?>