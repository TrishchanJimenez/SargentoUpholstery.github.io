<?php
session_start();

// Hardcoded credentials for testing (replace with your own logic)
$valid_username = "admin";
$valid_password = "password123";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate username and password
    if ($username === $valid_username && $password === $valid_password) {
        // Authentication successful, set session variables
        $_SESSION["username"] = $username;
        $_SESSION["is_authenticated"] = true;

        // Redirect to desired page after successful login
        header("Location: display_reviews.php");
        exit;
    } else {
        // Authentication failed, redirect back to login page with error
        header("Location: login.html?error=InvalidCredentials");
        exit;
    }
}
?>
