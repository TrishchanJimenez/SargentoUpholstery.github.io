<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    }
?>

<div class="confirm-arrival">
    <form class="form" method="post">
        <div class="form__wrapper form__wrapper--confirm">
            <h1 class="form__title">Confirm Arrival of Order</h1>
            <input class="form__submit form__submit--confirm" name="submit--confirm" type="submit" value="Confirm">
        </div>
    </form>
</div>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../notif.php');

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit--confirm"])) {
        try {
            // Write the query
            $query = "UPDATE `orders` SET `order_status` = 'received' WHERE order_id = :order_id;";
            // Prepare the query
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            // Execute the query
            $stmt->execute();
            echo '<script type="text/javascript"> alert("You have successfully confirmed the arrival of an order.") </script>';
            createNotif($_SESSION['user_id'], 'You have confirmed the arrival of Order #' . $order_id . '.', '/my/orders.php?order_id=' . $order_id);
            reloadPage();
        } catch (PDOException $e) {
            // Handle database error
            echo "<script>console.log(" . $e->getMessage() . ")</script>";
        }
    }
?>