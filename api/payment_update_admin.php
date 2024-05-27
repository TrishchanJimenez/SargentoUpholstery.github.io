<?php
    require '../database_connection.php'; 
    include('../notif.php');
    if(isset($_POST['payment_phase'])) {
        $payment_phase = $_POST['payment_phase'];
        $is_verified = $_POST['is_verified'] === "true" ? true : false;     
        $order_id = $_POST['order_id'];

        $query = "SELECT user_id, order_type FROM orders WHERE order_id = :order_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $order_type = $stmt->fetch(PDO::FETCH_ASSOC)['order_type'];
        $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];
        
        if($payment_phase === "downpayment" && $is_verified) {
            // Update downpayment verification status to verified
            $query = "UPDATE payment SET downpayment_verification_status = 'verified', payment_status = 'partially_paid' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                echo json_encode(["payment_status" => 'Verified']);
                // CHECK THE TYPE OF THE ORDER

                if($order_type === "mto") {
                    // Update order status to in_production
                    $query = "UPDATE orders SET order_status = 'in_production' WHERE order_id = :order_id";
                } else {
                    // Update order status to ready_for_pickup
                    $query = "UPDATE orders SET order_status = 'ready_for_pickup' WHERE order_id = :order_id";
                }

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':order_id', $order_id);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    // Update successful
                    echo json_encode(["order_status" => 'In Production']);
                    createNotif($user_id, "Your order is now in production", "/order.php?id=$order_id");
                } else {
                    // Update failed
                    echo json_encode(["error" => "Failed to Update"]);
                }
            } else {
                // Update failed
                echo json_encode(["error" => "Failed to Update"]);
            }
        } else if($payment_phase === "downpayment" && !$is_verified) {
            // Update downpayment verification status to needs_reverification
            $query = "UPDATE payment SET downpayment_verification_status = 'needs_reverification' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                echo json_encode(["payment_status" => 'Needs Reverification']);
                createNotif($user_id, "Your downpayment needs reverification", "/order.php?id=$order_id");
            } else {
                // Update failed
                echo "Failed to update downpayment verification status";
            }
        }
        if($payment_phase === "fullpayment" && $is_verified) {
            // Update downpayment verification status to verified
            $query = "UPDATE payment SET fullpayment_verification_status = 'verified', payment_status = 'fully_paid' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                echo "Downpayment verification status updated to verified";
            } else {
                // Update failed
                echo "Failed to update downpayment verification status";
            }
        } else if($payment_phase === "fullpayment" && !$is_verified) {
            // Update downpayment verification status to needs_reverification
            $query = "UPDATE Payment SET downpayment_verification_status = 'needs_reverification' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                echo "Downpayment verification status updated to needs_reverification";
            } else {
                // Update failed
                echo "Failed to update downpayment verification status";
            }
        }
    }
?>