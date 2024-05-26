<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    include_once("database_connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_SESSION['user_id'];

        // Prepare SQL query
        $sql = "UPDATE `chats` SET `is_read` = 1 WHERE `customer_id` = :user_id_receiver AND `sender_id` != :user_id_sender";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id_receiver', $user_id);
        $stmt->bindParam(':user_id_sender', $user_id);
        if ($stmt->execute()) {
            // Insertion successful, return success response
            http_response_code(200);
            exit(); // Exit to prevent further execution
        } else {
            // Error in insertion, return error response
            http_response_code(500);
            exit(); // Exit to prevent further execution
        }
    } else {
        // Invalid request
        http_response_code(400);
        exit(); // Exit to prevent further execution
    }
?>