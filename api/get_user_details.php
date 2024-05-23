<?php
    // Code for returning user details to a JavaScript fetch API 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    // User address
    if (isset($_SESSION['user_address'])) {
        echo json_encode(['address' => $_SESSION['user_address']]);
    } else {
        echo json_encode(['address' => null]);
    }
?>
