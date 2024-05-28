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
    <form class="form" method="post">
        <?php if($_SESSION['enablePickup']): ?>
            <div class="form__wrapper form__wrapper--pickup">
                <h1 class="form__title">Set Pickup Details</h1>

                <label class="form__label" for="pickup_method">Pickup Method</label>
                <select class="form__select" name="pickup_method" id="pickup_method" required>
                    <option class="form__option" value="third_party">Courier Service (Lalamove, LBC, etc.)</option>
                    <option class="form__option" value="self">Self Drop Off/Hand Delivery</option>
                </select>

                <label class="form__label" for="pickup_address">Pickup Address</label>
                <input class="form__input" type="text" id="pickup_address" name="pickup_address" value="<?= $af_address ?>" required>
            </div>
        <?php endif ?>
        <div class="form__wrapper form__wrapper--delivery">
            <h1 class="form__title">Set Delivery Details</h1>

            <label class="form__label" for="delivery_method">Delivery Method</label>
            <select class="form__select" name="delivery_method" id="delivery_method" required>
                <option class="form__option" value="third_party">Courier Service (Lalamove, LBC, etc.)</option>
                <option class="form__option" value="self">Self Pickup/Hand Delivery</option>
            </select>

            <label class="form__label" for="delivery_address">Delivery Address</label>
            <input class="form__select" type="text" id="delivery_address" name="delivery_address" value="<?= $af_address ?>" required>
        </div>
        <input class="form__submit" name="submit--soa" type="submit" value="Set Order Address/es">
    </form>
</div>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../api/CheckAddress.php');
    include_once('../notif.php');

    // Check if the form is submitted
    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit--soa"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit--soa"])) {
        // Sanitize and validate input (you can use more robust validation methods)
        $delivery_method = htmlspecialchars($_POST['delivery_method']);
        $delivery_address = htmlspecialchars(trim($_POST['delivery_address']));
        // Get the delivery address ID corresponding to the delivery address and user ID
        $delivery_address_id = getAddressId($delivery_address, $conn, $_SESSION['user_id']);
        
        try {
            // If order is of type repair
            if($_SESSION['enablePickup']) {
                $pickup_method = htmlspecialchars($_POST['pickup_method']);
                $pickup_address = htmlspecialchars(trim($_POST['pickup_address']));   
                // Get the pickup address ID corresponding to the pickup address and user ID
                $pickup_address_id = getAddressId($pickup_address, $conn, $_SESSION['user_id']);
                // Write the query
                $query_pickup = "UPDATE `pickup` SET `pickup_method` = :pickup_method, `pickup_address_id` = :pickup_address_id  WHERE `order_id` = :order_id";
                // Prepare the query
                $stmt_pickup = $conn->prepare($query_pickup);
                $stmt_pickup->bindParam(':pickup_method', $pickup_method);
                $stmt_pickup->bindParam(':pickup_address_id', $pickup_address_id);
                $stmt_pickup->bindParam(':order_id', $order_id);
            }
            // Write the query
            $query_delivery = "UPDATE `orders` SET `del_method` = :delivery_method, `del_address_id` = :delivery_address_id WHERE `order_id` = :order_id;";
            // Prepare the query
            $stmt_delivery = $conn->prepare($query_delivery);
            $stmt_delivery->bindParam(':delivery_method', $delivery_method);
            $stmt_delivery->bindParam(':delivery_address_id', $delivery_address_id);
            $stmt_delivery->bindParam(':order_id', $order_id);
            // Execute the queries
            if ($_SESSION['enablePickup']) {
                $stmt_pickup->execute();
            }
            $stmt_delivery->execute();
            echo '<script type="text/javascript"> alert("You have successfully set the address of an order.") </script>'; 
        } catch (PDOException $e) {
            // Handle database error
            echo "<script>console.log(" . $e->getMessage() . ")</script>";
        }
    }
?>