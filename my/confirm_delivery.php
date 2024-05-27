<?php
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the order ID is set in the POST data
        if (isset($_POST['order_id'])) {
            // Get the order ID from the POST data
            $order_id = $_POST['order_id'];
            
            // Update the order status to 'received' in the database
            $query = "UPDATE `orders` SET `order_status` = 'received' WHERE `order_id` = :order_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            
            // Execute the query
            if ($stmt->execute()) {
                // Return a success message
                echo json_encode(['success' => true]);
                exit;
            } else {
                // Return an error message
                echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
                exit;
            }
        } else {
            // Return an error message if order ID is not set in the POST data
            echo json_encode(['success' => false, 'message' => 'Order ID not provided']);
            exit;
        }
    } else {
        // Return an error message if the request method is not POST
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
?>