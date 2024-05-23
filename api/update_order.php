<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "sargento_1";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];

        // Update the `order_status` to 'received'
        $sql = "UPDATE orders SET order_status = 'received' WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id); // "i" denotes the type (integer) of the parameter

        if ($stmt->execute()) {
            // Close statement
            $stmt->close();
            // Close connection
            $conn->close();
            // Redirect to the original page
            header("Location: /my/user_orders.php#tab6");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }

    $conn->close();
?>