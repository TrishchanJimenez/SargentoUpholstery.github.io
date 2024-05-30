<?php
    session_start();
    require_once('../database_connection.php');
    include_once('../notif.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit--soa"])) {
        $delivery_method = htmlspecialchars($_POST['delivery_method']);
        $delivery_address = htmlspecialchars(trim($_POST['delivery_address']));
        $response = ["success" => false];

        try {
            if($_SESSION['enablePickup']) {
                $pickup_method = htmlspecialchars($_POST['pickup_method']);
                $pickup_address = htmlspecialchars(trim($_POST['pickup_address']));
                $query_pickup = "
                    INSERT INTO
                        `pickup` (
                            `order_id`,
                            `pickup_method`,
                            `pickup_address`
                        )
                    VALUES (
                        :order_id,
                        :pickup_method,
                        :pickup_address
                    )
                ";
                $stmt_pickup = $conn->prepare($query_pickup);
                $stmt_pickup->bindParam(':order_id', $order_id);
                $stmt_pickup->bindParam(':pickup_method', $pickup_method);
                $stmt_pickup->bindParam(':pickup_address', $pickup_address);
            }

            $query_delivery = "
                INSERT INTO 
                    `delivery` (
                        `order_id`,
                        `delivery_method`,
                        `delivery_address`
                    )
                VALUES (
                    :order_id,
                    :delivery_method,
                    :delivery_address
                )
            ";
            $stmt_delivery = $conn->prepare($query_delivery);
            $stmt_delivery->bindParam(':order_id', $order_id);
            $stmt_delivery->bindParam(':delivery_method', $delivery_method);
            $stmt_delivery->bindParam(':delivery_address', $delivery_address);

            if ($_SESSION['enablePickup']) {
                $stmt_pickup->execute();
            }
            $stmt_delivery->execute();

            $response["success"] = true;
            $response["message"] = "You have successfully set the address of an order.";

        } catch (PDOException $e) {
            $response["error"] = $e->getMessage();
        }

        echo json_encode($response);
    }
?>