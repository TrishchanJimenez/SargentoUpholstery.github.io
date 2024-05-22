<?php
    require '../database_connection.php';

    if(isset($_POST['content_id'])) {
        if(isset($_POST['content_text'])) {
            $content_id = $_POST['content_id'];
            $content_text = $_POST['content_text'];
            if(strpos($content_text, "'") !== false) {
                $content_text = str_replace("'", "\\'", $content_text);
            }
            $sql = "UPDATE contents SET content_text = :content_text WHERE content_id = :content_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':content_text', $content_text);
            $stmt->bindParam(':content_id', $content_id);
            $stmt->execute();
        }
    }
?>