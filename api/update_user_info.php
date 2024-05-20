<?php
    require '../database_connection.php';
    if(isset($_POST['info_type'])) {
        session_start();
        $id = $_SESSION['user_id'];
        $type = $_POST['info_type'];
        $new_value = $_POST['new_value'];

        if($type == 'name') {
            $stmt = $conn->prepare("UPDATE users SET name = :new_value WHERE user_id = :id");
        } else if($type == 'contact_number') {
            $stmt = $conn->prepare("UPDATE users SET contact_number = :new_value WHERE user_id = :id");
        } else if($type == 'email') {
            $stmt = $conn->prepare("UPDATE users SET email = :new_value WHERE user_id = :id");
        }
        $stmt->bindParam(':new_value', $new_value);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
    }
?>