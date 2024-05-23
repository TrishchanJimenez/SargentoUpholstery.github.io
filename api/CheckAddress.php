<?php
    function getAddressId($address, $conn, $user_id) :int {
        $stmt = $conn->prepare("SELECT * FROM addresses WHERE address = :address");
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        if ($stmt->rowCount() > 0) { 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['address_id']; 
        } else {
            $stmt = $conn->prepare("INSERT INTO addresses(address, user_id) VALUES(:address, :user_id)");
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $conn->lastInsertId();
        }
    }
?>