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

<div id="soaModal" class="modal">
    <div class="modal__content">
        <span class="modal__close" id="closeSOA">&times;</span>
        <form id="soaForm" class="form" method="post">
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
                <input class="form__input" type="text" id="delivery_address" name="delivery_address" value="<?= $af_address ?>" required>
            </div>
            <input class="form__submit" name="submit--soa" type="submit" value="Set Order Address/es">
        </form>
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

    /* Specific styles for different form wrappers */
    .form__wrapper--pickup {
        border-color: #28a745;
    }

    .form__wrapper--delivery {
        border-color: #ffc107;
    }
</style>

<script>
    document.getElementById("soaForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        fetch("../api/submit_order_address.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                console.error("Error:", data.error);
                alert("An error occurred: " + data.error);
            }
        })
        .catch(error => console.error("Fetch error:", error));
    });
</script>