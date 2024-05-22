<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="admin-chat__title">Admin Chat</title>
    <link rel="stylesheet" href="../css/admin/chat.css">
</head>
<body class="admin-chat__body">
    <div class="admin-chat">
        <!-- Sidebar -->
        <?php require('sidebar.php') ?>
        <!-- Main Content -->
        <div class="admin-chat__main-content">
            <!-- Header -->
            <header class="admin-chat__header">
                <h1 class="admin-chat__heading">Admin Chat</h1>
            </header>
            <div class="admin-chat__container">
                <!-- Customer List -->
                <div class="admin-chat__customer-list">
                    <h2 class="admin-chat__customer-heading">Customers</h2>
                    <ul class="admin-chat__customer-list-items">
                        <!-- Customer list items will be dynamically populated here -->
                    </ul>
                </div>
                <!-- Chat Window -->
                <div class="admin-chat__chat-window">
                    <h2 class="admin-chat__chat-heading">Chat with Customer</h2>
                    <div class="admin-chat__messages">
                        <!-- Chat messages will be displayed here -->
                    </div>
                    <form id="message-form" class="admin-chat__message-form" method="post">
                        <input type="text" id="message-input" class="admin-chat__message-input" placeholder="Type a message...">
                        <button type="submit" class="admin-chat__send-button">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/admin/chat.js"></script>
</body>
</html>