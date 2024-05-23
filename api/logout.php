<?php
    session_start(); // Start the session
    session_destroy(); // Destroy the session
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php'); // Redirect to login.php
    exit(); // Ensure no further code is executed after redirection
?>