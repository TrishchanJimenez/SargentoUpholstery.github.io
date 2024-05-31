<?php
    require dirname(__DIR__) . '/database_connection.php';
    include  dirname(__DIR__) . '/notif.php';
    if(isset($_POST['status_type'])) {
        $status_type = $_POST['status_type'];
        // $is_multiple = $_POST['is_multiple'] === "true" ? true : false;

        $updateStmt = null;
        if($status_type === "prod") {
            // echo "prod";
            $updateStmt = $conn->prepare("UPDATE orders SET order_phase = :status WHERE order_id = :id");
        } else if($status_type === "payment") {
            $updateStmt = $conn->prepare("UPDATE payment SET payment_phase = :status WHERE order_id = :id");
        }

        $statuses = [
            "pending_downpayment",
            "awaiting_furniture",
            "in_production",
            "pending_fullpayment",
            "out_for_delivery",
            "received"
        ];

        $sql = "SELECT * FROM orders JOIN quotes USING(quote_id) WHERE order_id = :id";
        $selectStmt = $conn->prepare($sql);
        $selectStmt->bindParam(':id', $_POST['order_id']);
        $selectStmt->execute();
        $order = $selectStmt->fetch(PDO::FETCH_ASSOC);

        $new_status = str_replace("-", "_", $_POST['new_status']);
        $order_id = $_POST['order_id'];
        $user_id = $order['customer_id'];

        if($new_status === "awaiting_furniture") {
            $checkQuery = "SELECT pickup_method FROM pickup WHERE order_id = :order_id";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(":order_id", $order_id);
            $checkStmt->execute();
            $recordCount = $checkStmt->rowCount();
            if ($recordCount > 0) {
                // Record found
                createNotif($customerId, "Your order is now set to awaiting furniture, please wait for us to pickup your furniture or drop it off at our location", "/my/orders.php?order_id=$order_id");
            } else {
                $record = $checkStmt->fetch(PDO::FETCH_ASSOC);
                // $pickupMethod = $record['pickup_method'];
                if (isset($record['pickup_method'])) {
                    createNotif($customerId, "Your order is now set to awaiting furniture, please wait for us to pickup your furniture or drop it off at our location", "/my/orders.php?order_id=$order_id");
                    // Pickup method is not set
                    // Add your code here
                } else {
                    createNotif($customerId, "Your order is now set to awaiting furniture, please set your pickup address", "/my/orders.php?order_id=$order_id");
                    // Pickup method is set
                    // Add your code here
                }
                // Add your code here
            }
        } else if ($new_status === "") {

        }
        

        $updateStmt->bindParam(':status', $new_status);
        $updateStmt->bindParam(':id', $order_id);
        $updateStmt->execute();
        echo json_encode([
            "status" => $status_type,
            "new_status" => $new_status,
            "order_id" => $order_id
        ]);
    }
?>