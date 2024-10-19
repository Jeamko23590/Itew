<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Check if "Remember Me" was checked during login
if (!isset($_COOKIE["remember"]) || $_COOKIE["remember"] !== "1") {
    // If "Remember Me" was not checked, delete all cookies
    if (isset($_COOKIE["user"])) {
        setcookie("user", "", time() - 3600, "/", '', true, true);
    }
    if (isset($_COOKIE["email"])) {
        setcookie("email", "", time() - 3600, "/", '', true, true);
    }
    if (isset($_COOKIE["password"])) {
        setcookie("password", "", time() - 3600, "/", '', true, true);
    }
}

// Redirect to index.php
header("Location: index.php");
exit;
?>