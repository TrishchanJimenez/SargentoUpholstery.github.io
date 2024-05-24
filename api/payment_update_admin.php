<?php
    require '../database_connection.php'; 
    if(isset($_POST['payment_phase'])) {
        $payment_phase = $_POST['payment_phase'];
        $is_verified = $_POST['is_verified'] === "true" ? true : false;     
        
        if($payment_phase === "downpayment" && $is_verified) {
            // Update downpayment verification status to verified
            $query = "UPDATE payment SET downpayment_verification_status = 'verified', payment_status = 'partially_paid' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                echo json_encode(["payment_status" => 'verified']);
            } else {
                // Update failed
                echo json_encode(["error" => "Failed to Update"]);
            }
        } else if($payment_phase === "downpayment" && !$is_verified) {
            // Update downpayment verification status to needs_reverification
            $query = "UPDATE Payment SET downpayment_verification_status = 'needs_reverification' WHERE order_id = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                // Update successful
                echo json_encode(["payment_status" => 'verified']);
            } else {
                // Update failed
                echo "Failed to update downpayment verification status";
            }
        }
        if($payment_phase === "fullpayment" && $is_verified) {
            // Update downpayment verification status to verified
            $query = "UPDATE Payment SET fullpayment_verification_status = 'verified', payment_status = 'fully_paid' WHERE order_id = :order_id";
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