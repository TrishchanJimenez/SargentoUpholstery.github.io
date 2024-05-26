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
?>

<link rel="stylesheet" href="css/chat.css">

<div class="chat-button" id="chatButton">
    <img src="/websiteimages/icons/chat-icon.svg" alt="Chat Logo" class="chat-button__image">
    <div class="chat-button__new-message-indicator" id="newMessageIndicator"></div>
</div>
<div class="chat-window" id="chatWindow">
    <div class="chat-window__header">
        <h2 class="chat-window__title">Chat</h2>
        <button class="chat-window__close" id="closeChatWindow">&times;</button>
    </div>
    <div class="chat-window__messages" id="chatMessages">
        <?php
            $lastMessageId = 0;
            // Fetch and display messages
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sender_id = $row['sender_id'];
                $message = $row['message'];
                $timestamp = strtotime($row['timestamp']); // Convert timestamp to UNIX timestamp

                // Calculate time difference relative to current time
                $time_diff = time() - $timestamp;
                $time_ago = '';

                if ($time_diff < 60) {
                    $time_ago = 'Just now';
                } elseif ($time_diff < 3600) {
                    $time_ago = floor($time_diff / 60) . ' minutes ago';
                } elseif ($time_diff < 86400) {
                    $time_ago = floor($time_diff / 3600) . ' hours ago';
                } elseif ($time_diff < 604800) {
                    $time_ago = floor($time_diff / 86400) . ' days ago';
                } elseif ($time_diff < 2592000) {
                    $time_ago = floor($time_diff / 604800) . ' weeks ago';
                } else {
                    $time_ago = date('F j, Y', $timestamp); // Default to standard date format for older messages
                }

                // Determine message alignment based on sender_id
                $messageClass = ($sender_id == $user_id) ? 'right' : 'left';

                // Output message with proper alignment and formatted timestamp
                echo "<div class='message " . $messageClass . "'>";
                echo "<span class='timestamp'>" . $time_ago . "</span>";
                echo "<p>" . $message . "</p>";
                echo "</div>";

                $lastMessageId = $row['chat_id'];
            }
        ?>
    </div>
    <div class="chat-window__input">
        <input type="text" class="chat-window__input-text" id="chatInput" placeholder="Type a message...">
        <button class="chat-window__input-button" id="sendButton">Send</button>
    </div>
</div>

<script>var lastMessageId = <?php echo $lastMessageId ?></script>
<script src="js/chat.js"></script>