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

<div class="modal" id="confirmDeliveryModal">
    <div class="modal__content">
        <span class="modal__close" id="closeConfirmDelivery">&times;</span>
        <div class="form__wrapper form__wrapper--confirmDelivery">
            <h1 class="form__title">Confirm Arrival of Order</h1>
            <form id="confirmDeliveryForm" class="form" method="post">
                <p>Confirm the arrival of your order at the set delivery address?</p>
                <input class="form__submit form__submit--confirm" name="submit" type="submit" value="Confirm">
            </form>
        </div>
    </div>
</div>

<style>
    /* ---------- Modal ---------- */

    /* General modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
        justify-content: center; /* Center the modal content horizontally */
        align-items: center; /* Center the modal content vertically */
        font-family: "Inter", sans-serif;
    }

    .modal__content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: fit-content;
        max-width: 80%; /* Optional: Limit modal width */
        height: fit-content;
        max-height: 80%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative; /* Required for close button positioning */
        overflow: auto;
    }

    /* Close button styles */
    .modal__close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
    }

    .modal__close:hover,
    .modal__close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    /* General form styles */
    .form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        margin-top: 5vmin;
    }

    .form__wrapper {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
    }

    .form__title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .form__label {
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .form__select, 
    .form__input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .form__select {
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%23333" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 9l-7 7-7-7"></path></svg>') no-repeat right 10px center;
        background-size: 16px 16px;
    }

    .form__option {
        padding: 10px;
        font-size: 16px;
    }

    .form__input {
        box-sizing: border-box;
    }

    .form__submit {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form__submit:hover {
        background-color: #0056b3;
    }
</style>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../notif.php');
    include_once('../alert.php');

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        try {
            // Write the query
            $query = "UPDATE `orders` SET `order_phase` = 'received' WHERE order_id = :order_id";
            // Prepare the query
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            // Execute the query
            if($stmt->execute()) {
                sendAlert("success", "You have successfully confirmed the arrival of an order.");
                createNotif($_SESSION['user_id'], 'You have confirmed the arrival of Order #' . $order_id . '.', '/my/orders.php?order_id=' . $order_id);
            }
        } catch (PDOException $e) {
            echo "<script> console.log(" . $e->getMessage() . ") </script>";
        }
    }
?>