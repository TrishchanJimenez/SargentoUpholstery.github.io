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
        $autofill_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $autofill_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
        $autofill_contact_number = isset($_SESSION['contact_number']) ? $_SESSION['contact_number'] : '';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/order.css">
</head>

<body>
    <?php 
        include_once('database_connection.php');
        include_once('header.php')
    ?>
        <div class="faq">
            <h1 class="faq__title">Frequently Asked Questions</h1>
            <ol class="faq__list">
                <?php
                    $sql = "SELECT * FROM faqs";
                    $stmt = $conn->query($sql);
                    $faqs = $stmt->fetchAll();
                    foreach ($faqs as $faq) {
                        echo "
                            <div class='faq__item'>
                                <li class='faq__question'>{$faq['question']}</li>
                                <p class='faq__answer'>{$faq['answer']}</p>
                            </div>
                        ";
                    }
                ?>
            </ol>
        </div>
    </div>
    <?php 
        // require_once('outro.php');
        require_once('footer.php');
    ?>
    <script src="js/globals.js"></script>
</body>
</html>