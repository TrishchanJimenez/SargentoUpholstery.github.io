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