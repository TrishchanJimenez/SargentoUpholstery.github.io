<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    include_once("database_connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_SESSION['user_id'];
        $last_message_id = $_POST['lastMessageId'];

        // Prepare SQL query
        $sql = "SELECT * FROM `chats` WHERE `customer_id` = :user_id_receiver AND `sender_id` != :user_id_sender AND `chat_id` > :last_message_id ORDER BY `timestamp`";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id_receiver', $user_id);
        $stmt->bindParam(':user_id_sender', $user_id);
        $stmt->bindParam(':last_message_id', $last_message_id);
        if ($stmt->execute()) {
            date_default_timezone_set('Asia/Manila'); // Set timezone to Asia/Manila
            $messages = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $time_diff = time() - strtotime($row['timestamp']);
                $messages[] = [
                    'sender_id' => $row['sender_id'],
                    'message' => $row['message'],
                    'time_diff' => $time_diff
                ];
                $last_message_id = $row['chat_id'];
            }
            echo json_encode(['messages' => $messages, 'newLastMessageId' => $last_message_id]);
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