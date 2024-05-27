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
        <?php if($enablePickup): ?>
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
        <input class="form__submit" type="submit" value="Set Order Address/es">
    </form>
</div>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../api/CheckAddress.php');
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        // Retrieve form data
        $delivery_method = $_POST['delivery_method'];
        $delivery_address = $_POST['delivery_address'];

        // Sanitize and validate input (you can use more robust validation methods)
        $delivery_method = htmlspecialchars($delivery_method);
        $delivery_address = htmlspecialchars($delivery_address);

        $delivery_address_id = getAddressId($delivery_address, $conn, $_SESSION['user_id']);

        if($enablePickup) {
            $pickup_method = $_POST['pickup_method'];
            $pickup_address = $_POST['pickup_address'];
            $pickup_method = htmlspecialchars($pickup_method);
            $pickup_address = htmlspecialchars($pickup_address);    

            $pickup_address_id = getAddressId($pickup_address, $conn, $_SESSION['user_id']);
            try {
                $query = "
                    UPDATE 
                        pickup
                    SET 
                        pickup_method = :pickup_method, 
                        pickup_address_id = :pickup_address_id
                    WHERE
                        order_id = :order_id
                ";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':pickup_method', $pickup_method);
                $stmt->bindParam(':pickup_address_id', $pickup_address_id);
                $stmt->bindParam(':order_id', $order_id);

                // Execute the query
                $stmt->execute();
            } catch (PDOException $e) {
                // Handle database error
                echo $e->getMessage();
            }
        }
        
        try {
            // Update database table with the new address information
            $query = "
                UPDATE 
                    orders
                SET 
                    del_method = :delivery_method, 
                    del_address_id = :delivery_address_id
                WHERE 
                    order_id = :order_id;
            ";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':delivery_method', $delivery_method);
            $stmt->bindParam(':delivery_address_id', $delivery_address_id);
            $stmt->bindParam(':order_id', $order_id);

            // Execute the query
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database error
            echo $e->getMessage();
        }
    }
?>
