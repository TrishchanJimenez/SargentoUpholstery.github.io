<?php
    require '../database_connection.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // var_dump($_POST);
        // echo "outside";
        $action = trim($_POST['action'], '"'); 
        switch ($action) {
            case 'CREATE':
                // echo 'test';
            // Code for CREATE action
                session_start();
                $new_address = $_POST['new_address'];
                $stmt = $conn->prepare("INSERT INTO addresses (user_id, address) VALUES (:id, :new_address)");
                $stmt->bindParam(':id', $_SESSION['user_id']);
                $stmt->bindParam(':new_address', $new_address);
                $stmt->execute();
                $last_insert_id = $conn->lastInsertId();
                echo json_encode(['address_id' => $last_insert_id, 'address' => $new_address]);
                break;
            case 'UPDATE':
                // echo 'testupdate';
                var_dump($_POST);
                $address_id = $_POST['address_id'];
                $new_address = $_POST['new_address'];
                $stmt = $conn->prepare("UPDATE addresses SET address = :new_address WHERE address_id = :address_id");
                $stmt->bindParam(':new_address', $new_address);
                $stmt->bindParam(':address_id', $address_id);
                $stmt->execute();
                break;
            case 'DELETE':
                // echo 'testdelete';
                // Code for DELETE action
                $address_id = $_POST['address_id'];
                $stmt = $conn->prepare("DELETE FROM addresses WHERE address_id = :address_id");
                $stmt->bindParam(':address_id', $address_id);
                $stmt->execute();
                echo json_encode(['address_id' => $address_id]);
                break;
        }
    }
?>