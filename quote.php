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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/quote.css">
</head>

<body>
    <?php 
        include_once('database_connection.php');
        // Get all addresses of the user
        $query = "SELECT * FROM `addresses` WHERE `user_id` = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $addresses = $stmt->fetchAll(); 
    ?>
    <?php
        // APIs
        include_once("alert.php");
        include_once("api/CheckAddress.php");
    ?>
    <?php 
        require_once('header.php');
        $needs_cta = false;
        // require_once('intro.php');
    ?>
    <div class="quote-page__content">
        <div class="quote">
            <form class="quote-form" method="POST">
                <div class="quote-form__heading">
                    <h1 class="quote-form__title">Quotation Form</h1>
                    <p class="quote-form__subtext">
                        Request a quote or an estimate price from us. 
                        Simply fill in the necessary details & submit the form, and we will review your quote as soon as possible.<br>
                        <br>
                        *All fields with an asterisk are required.
                    </p>
                </div>
                <!-- Quote Form mismo -->
                <div class="quote-form__wrapper">
                    <!-- Personal Information Section -->
                    <div class="quote-form__display" id="personal-information">
                        <h2 class="quote-form__header">Personal Information</h2>
                        <p class="quote-form__description">Please ensure that your personal information is correct.</p>
                        
                        <div class="quote-form__output-container">
                            <label class="quote-form__label" for="customer-name">Full Name *</label> 
                            <input class="quote-form__output quote-form__output--text" type="text" id="customer-name" name="customer-name" required readonly>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="customer-address">Address *</label> 
                            <select class="quote-form__output quote-form__output--select" name="customer-address" id="customer-address" required readonly>
                                <option value="">Address 1</option>
                                <option value="">Address 2</option>
                            </select>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="customer-contact">Contact Number *</label> 
                            <div class="quote-form__input-text--contact">
                                <select class="quote-form__output" id="country-code" name="country-code" required>
                                    <option value="+63">+63</option>
                                </select>
                                <input class="quote-form__input quote-form__input--text" type="tel" id="customer-contact" name="customer-contact" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Furniture Details Section -->
                    <div class="quote-form__section" id="details">
                        <h2 class="quote-form__header">Furniture Details</h2>
                        <p class="quote-form__description">Please provide information about the furniture.</p>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="furniture-type">Furniture Type *</label> 
                            <input class="quote-form__input quote-form__input--text" type="text" id="furniture-type" name="furniture-type" required>
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="furniture-description">Furniture Description *</label> 
                            <textarea class="quote-form__input quote-form__input--textarea" id="furniture-description" name="furniture-description" required></textarea>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--file">
                            <label class="quote-form__label" for="furniture-reference-image">Reference Image</label> 
                            <input class="quote-form__input quote-form__input--file" type="file" id="furniture-reference-image" name="furniture-reference-image" accept="images/*">
                        </div>

                        <div class="quote-form__input-container">
                            <label class="quote-form__label" for="furniture-quantity">Quantity *</label> 
                            <input class="quote-form__input quote-form__input--number" type="number" id="furniture-quantity" name="furniture-quantity" value="1" min="1" required>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="furniture-enable-customization" name="furniture-enable-customization">
                            <label class="quote-form__label" for="furniture-enable-customization">Add Customization</label>
                        </div>
                    </div>

                    <!-- Legal Agreements Section -->
                    <div class="quote-form__section" id="legal-agreements">
                        <h2 class="quote-form__header">Legal Agreements</h2>
                        <p class="quote-form__description">Please read our terms and conditions & data privacy notice.</p>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-terms" name="legal-terms" required>
                            <label class="quote-form__label" for="legal-terms">I have read and agree to the <a href="/legal-agreements#terms-and-conditions" target="_blank">Terms and Conditions</a></label>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox">
                            <input class="quote-form__input quote-form__input--checkbox" type="checkbox" id="legal-data" name="legal-data" required>
                            <label class="quote-form__label" for="legal-data">I have read and agree to the <a href="/legal-agreements#data-privacy" target="_blank">Data Privacy Notice</a></label>
                        </div>

                        <!-- Submit Button -->
                        <div class="quote-form__heading-actions">
                            <button class="quote-form__submit" type="submit">Submit Request</button>
                        </div>
                    </div>

                    <!-- Customization Section -->
                    <div class="quote-form__section" id="customization">
                        <h2 class="quote-form__header">Furniture Customization</h2>
                        <p class="quote-form__description">Customize the furniture.</p>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <input class="quote-form__input quote-form__input--checkbox quote-form__input--customization" type="checkbox" id="customization-enable-dimensions" name="customization-enable-dimensions">
                            <label class="quote-form__label quote-form__label--customization" for="customization-enable-dimensions">Specify Dimensions</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="customization-dimensions" name="customization-dimensions" placeholder="Length x Width x Height" disabled>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <input class="quote-form__input quote-form__input--checkbox quote-form__input--customization" type="checkbox" id="customization-enable-materials" name="customization-enable-materials">
                            <label class="quote-form__label quote-form__label--customization" for="customization-enable-materials">Specify Materials</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="customization-materials" name="customization-materials" placeholder="E.g. wood, plastic, metal" disabled>
                        </div>

                        <div class="quote-form__input-container quote-form__input-container--checkbox quote-form__input-container--customization">
                            <input class="quote-form__input quote-form__input--checkbox quote-form__input--customization" type="checkbox" id="customization-enable-fabric" name="customization-enable-fabric">
                            <label class="quote-form__label quote-form__label--customization" for="customization-enable-fabric">Specify Fabric</label>
                            <input class="quote-form__input quote-form__input--text quote-form__input--customization" type="text" id="customization-fabric" name="customization-fabric" placeholder="E.g cotton, linen, leather" disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php 
        // require_once('outro.php');
        require_once('footer.php');
    ?>
    <script src="js/globals.js"></script>
    <script src="js/quote.js"></script>
</body>
</html>