<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    } else {
        $af_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';
    }
?>
<div class="soa">
    <form class="soa__form" method="post">
        <?php if($enablePickup): ?>
        <div class="pickup__wrapper">
            <h1 class="pickup__title">Set Pickup Details</h1>

            <label for="pickup_method">Pickup Method</label>
            <select name="pickup_method" id="pickup_method" required>
                <option value="third_party">Courier Service (Lalamove, LBC, etc.)</option>
                <option value="self">Self Drop Off/Hand Delivery</option>
            </select>

            <label for="pickup_address">Pickup Address</label>
            <input type="text" id="pickup_address" name="pickup_address" value="<?= $af_address ?>" required>
        </div>
        <?php endif ?>
        <div class="delivery__wrapper">
            <h1 class="delivery__title">Set Delivery Details</h1>

            <label for="delivery_method">Delivery Method</label>
            <select name="delivery_method" id="delivery_method" required>
                <option value="third_party">Courier Service (Lalamove, LBC, etc.)</option>
                <option value="self">Self Drop Off/Hand Delivery</option>
            </select>

            <label for="delivery_address">Delivery Address</label>
            <input type="text" id="delivery_address" name="delivery_address" value="<?= $af_address ?>" required>
        </div>
        <input type="submit" value="Set Order Address/es">
    </form>
</div>
<?php
    // Include database connection
    require_once('database_connection.php');
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $pickup_method = $_POST['pickup_method'];
        $pickup_address = $_POST['pickup_address'];
        $delivery_method = $_POST['delivery_method'];
        $delivery_address = $_POST['delivery_address'];

        // Sanitize and validate input (you can use more robust validation methods)
        $pickup_method = htmlspecialchars($pickup_method);
        $pickup_address = htmlspecialchars($pickup_address);
        $delivery_method = htmlspecialchars($delivery_method);
        $delivery_address = htmlspecialchars($delivery_address);

        // Update database table with the new address information
        $query = "
            UPDATE 
                orders o
                JOIN
                pickup p 
                ON o.order_id = p.order_id
            SET 
                p.pickup_method = :pickup_method, 
                p.pickup_address_id = (
                    SELECT
                        address_id
                    FROM
                        addresses
                    WHERE
                        user_id = :user_id
                        AND
                        address = :pickup_address
                ), 
                o.del_method = :delivery_method, 
                o.del_address_id = (
                    SELECT
                        address_id
                    FROM
                        addresses
                    WHERE
                        user_id = :user_id
                        AND
                        address = :del_address
                )
            WHERE 
                o.user_id = :user_id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pickup_method', $pickup_method);
        $stmt->bindParam(':pickup_address', $pickup_address);
        $stmt->bindParam(':delivery_method', $delivery_method);
        $stmt->bindParam(':delivery_address', $delivery_address);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect or provide feedback to the user upon successful update
            sendAlert("success", "Let's fucking gooo");
            exit;
        } else {
            // Handle database error
            sendAlert("error", "Noooo bobo ka");
        }
    }
?>
