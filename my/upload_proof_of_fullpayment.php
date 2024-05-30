<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!-- Modal for Upload Proof of Fullpayment -->
<div class="modal" id="upofModal">
    <div class="modal__content">
        <span class="modal__close" id="closeUPOF">&times;</span>
        <div class="form__wrapper form__wrapper--upload">
            <h1 class="form__title">Upload Proof of Fullpayment</h1>
            <form id="upofForm" class="form" method="post" enctype="multipart/form-data">
                <label class="form__label" for="payment_method">Payment Method</label>
                <select class="form__select" name="payment_method" id="payment_method">
                    <option class="form__option" value="gcash">GCash</option>
                    <option class="form__option" value="paymaya">Paymaya</option>
                    <option class="form__option" value="cash">Cash</option>
                </select>

                <label class="form__label" for="account_holder">Account Holder Name</label>
                <input class="form__input" type="text" id="account_holder" name="account_holder" required>

                <label class="form__label" for="amount">Amount</label>
                <input class="form__input" type="number" id="amount" name="amount" required>

                <label class="form__label" for="reference_no">Reference No. (For cash payments, enter N/A instead)</label>
                <input class="form__input" type="text" id="reference_no" name="reference_no" required>

                <label class="form__label" for="proof_upload">Upload File</label>
                <input class="form__input" type="file" id="proof_upload" name="proof_upload" accept="image/*,application/pdf" required>

                <p class="form__note">Accepted formats: JPEG, PNG, PDF. Maximum size: 5MB.</p>
                <input class="form__submit" type="submit" name="submit--upof" value="Submit Proof">
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

<script>
    document.getElementById("upofForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        fetch("../api/submit_proof_of_fullpayment.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                console.error("Error:", data.error);
            }
        })
        .catch(error => console.error("Fetch error:", error));
    });
</script>