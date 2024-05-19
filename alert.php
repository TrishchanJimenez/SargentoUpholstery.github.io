<?php
function sendAlert($messageType, $message) {
    // success, error, warning, info
    if ((isset($message) && isset($messageType) && !empty($message) && !empty($messageType))) {
        echo '<div class="alert alert--' . htmlspecialchars($messageType) . '">';
        echo '<span class="alert__message">' . htmlspecialchars($message) . '</span>';
        echo '<button class="alert__close-button">X</button>';
        echo '</div>';
    }
}
?>