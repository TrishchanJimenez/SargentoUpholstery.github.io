<link rel="stylesheet" href="/css/alert.css">
<?php
function sendAlert($messageType, $message) {
    // success, error, warning, info
    if ((isset($message) && isset($messageType) && !empty($message) && !empty($messageType))) {
        echo '<div class="alert alert--' . htmlspecialchars($messageType) . '">';
        echo '<span class="alert__message">' . htmlspecialchars($message) . '</span>';
        echo '<button class="alert__close-button" onclick="this.parentElement.remove()">X</button>';
        echo '</div>';
    }
}
?>