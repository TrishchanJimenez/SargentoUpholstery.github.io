<?php
    require dirname(__DIR__) . '/database_connection.php';
    if(isset($_POST['status_type'])) {
        $status_type = $_POST['status_type'];
        $is_multiple = $_POST['is_multiple'] === "true" ? true : false;

        $updateStmt = null;
        if($status_type === "prod") {
            // echo "prod";
            $updateStmt = $conn->prepare("UPDATE orders SET order_status = :status WHERE order_id = :id");
        } else if($status_type === "payment") {
            $updateStmt = $conn->prepare("UPDATE payment SET payment_status = :status WHERE order_id = :id");
        }

        $statuses = [
            "new_order",
            "pending_downpayment",
            "ready_for_pickup",
            "in_production",
            "pending_fullpayment",
            "out_for_delivery",
            "received"
        ];

        if($is_multiple) {
            $array = $_POST['order_id'];
            $sql = "SELECT * FROM orders WHERE order_id IN (";
            foreach($array as $id) {
                $sql .= $id . ",";
            }
            $sql = rtrim($sql, ',');
            $sql .= ")";
            $selectStmt = $conn->query($sql);
            $selectStmt ->execute();
            $orders = $selectStmt->fetchAll(PDO::FETCH_ASSOC);
            $response = [];
            foreach($orders as $order) {
                if($order['order_status'] === "received") {
                    continue;
                }
                $new_status = $statuses[array_search($order['order_status'], $statuses) + 1];
                if($order['order_type'] === "mto" && $new_status === "ready_for_pickup") {
                    $new_status = "in_production";
                }
                $updateStmt->bindParam(':id', $order['order_id']);
                $updateStmt->bindParam(':status', $new_status);
                $updateStmt->execute();
                $response[] = [
                    "order_id" => $order['order_id'],
                    "new_status" => $new_status
                ];
            }
            echo json_encode($response);
        } else {
            $new_status = str_replace("-", "_", $_POST['new_status']);
            $order_id = $_POST['order_id'];
            $updateStmt->bindParam(':status', $new_status);
            $updateStmt->bindParam(':id', $order_id);
            $updateStmt->execute();
            echo json_encode([
                "status" => $status_type,
                "new_status" => $new_status,
                "order_id" => $order_id
            ]);
        }
    }
?>