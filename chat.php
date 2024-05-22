<?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Include database connection
    include_once("database_connection.php");

    // Fetch messages belonging to the current user
    $user_id = $_SESSION['user_id'];

    // Prepare SQL query
    $sql = "SELECT * FROM `chats` WHERE `customer_id` = :user_id ORDER BY `timestamp` ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // print_r($stmt->fetch(PDO::FETCH_ASSOC));
?>

<link rel="stylesheet" href="css/chat.css">

<div class="chat-button" id="chatButton">
    <img src="/websiteimages/icons/chat-icon.svg" alt="Chat Logo" class="chat-button__image">
</div>
<div class="chat-window" id="chatWindow">
    <div class="chat-window__header">
        <h2 class="chat-window__title">Chat</h2>
        <button class="chat-window__close" id="closeChatWindow">&times;</button>
    </div>
    <div class="chat-window__messages" id="chatMessages">
        <?php
            // Fetch and display messages
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sender_id = $row['sender_id'];
                $message = $row['message'];
                $timestamp = $row['timestamp'];

                // Determine message alignment based on sender_id
                $messageClass = ($sender_id == $user_id) ? 'right' : 'left';

                // Output message with proper alignment
                echo "<div class='message " . $messageClass . "'>";
                echo "<span class='timestamp'>" . $timestamp . "</span>";
                echo "<p>" . $message . "</p>";
                echo "</div>";
            }
        ?>
    </div>
    <div class="chat-window__input">
        <input type="text" class="chat-window__input-text" id="chatInput" placeholder="Type a message...">
        <button class="chat-window__input-button" id="sendButton">Send</button>
    </div>
</div>

<script src="js/chat.js"></script>