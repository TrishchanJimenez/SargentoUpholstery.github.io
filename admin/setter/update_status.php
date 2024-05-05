<?php
    require '../../database_connection.php';
    if(isset($_POST['new_status'])) {
        $status_type = $_POST['status_type'];
        $new_status = $_POST['new_status'];
        $order_id = $_POST['order_id'];

        $new_status = str_replace("-", "_", $new_status);
        if($new_status === "pending_downpayment") $new_status = "pending_first_installment";
        else if ($new_status === "pending_fullpayment") $new_status = "pending_second_installment";

        if($status_type === "prod") {
            $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        } else if($status_type === "payment") {
            $stmt = $conn->prepare("UPDATE payment SET payment_status = ? WHERE order_id = ?");
        }

        $stmt->execute([$new_status, $order_id]);
        echo json_encode([
            "status" => $status_type,
            "new_status" => $new_status,
            "order_id" => $order_id
        ]);
    }
?>