<?php
require 'db_config.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $email, $username, $password);
        if ($stmt->execute()) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $stmt->insert_id;
            $_SESSION["username"] = $username;
            setcookie("user", $username, time() + (86400 * 30), "/"); // 30 days

            // Redirect to the dashboard
            header("location: dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>