<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Include database connection file
    include_once("database_connection.php");
    include_once("notif.php");

    // Fetch all records from notifs table where user_id matches session user_id
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM `notifs` WHERE `user_id` = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $notif_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are unread notifications
    $unread_notifs = false;
    foreach ($notif_list as $notif) {
        if ($notif['is_read'] == 0) {
            $unread_notifs = true;
            break;
        }
    }
}
?>
<header class="header">
    <a class="business-brand" href="index.php">
        <img src="/websiteimages/sargento_logo.png" alt="" class="business-logo">
        <h1 class="business-name text-gold">Sargento Upholstery</h1>
        <h2 class="business-category">Textile Furniture Service</h2>
    </a>
    <nav class="main-menu">
        <div class="nav-icons">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo "
                    <div class='notif'>
                        <img class='notif__bell' src='/websiteimages/icons/bell-icon.svg' alt='' width='36px' height='36px'>
                ";
                if ($unread_notifs) {
                    echo "<div class='notif__dot'></div>";
                }
                echo "
                    </div>
                    <img src='/websiteimages/icons/account-icon.svg' alt='' id='account-btn' width='36px' height='36px'>
                    <div id='notifContainer' class='notif__container'>
                        <div id='notifContent' class='notif__content'>
                ";
                foreach ($notif_list as $notif) {
                    $is_read = $notif['is_read'] ? "" : "unread";
                    echo "
                            <div class='notif__message $is_read' data-notif-id='{$notif['notif_id']}'>{$notif['notif_msg']} 
                            <button class='mark-read-btn'>Mark as Read</button></div>
                    ";
                }
                echo "
                        </div>
                    </div>
                ";
            } else {
                echo "
                <a href='login.php'>
                    <img src='/websiteimages/icons/account-icon.svg' alt='' id='account-btn' width='36px' height='36px'>
                </a>
                ";
            }
            ?>
            <div class="account-menu">
                <?php
                if ($_SESSION['access_type'] === "admin") {
                    echo "
                            <a href='/admin/dashboard.php'>
                                <img src='/websiteimages/icons/person-icon.svg' alt=''>
                                <span>
                                    Admin Panel
                                </span>
                            </a>
                        ";
                    } else {
                        echo "
                            <a href='/my/account.php'>
                                <img src='/websiteimages/icons/person-icon.svg' alt=''>
                                <span>My Account</span>
                            </a>
                            <a href='/user_orders.php'>
                                <img src='/websiteimages/icons/order-icon.svg' alt=''>
                                <span>My Orders</span>
                            </a>
                        ";
                    }
                ?>
                <form method="post">
                    <button type="submit" name="logout">
                        <img src="/websiteimages/icons/signout-icon.svg" alt="">
                        <span>Log Out</span>
                    </button>
                </form>
                <?php
                if (isset($_POST['logout'])) {
                    session_destroy();
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
                ?>
            </div>
            <img src="/websiteimages/icons/menu-icon.svg" alt="" id="open-btn" width="36px" height="36px">
        </div>
        <div id="offcanvas-menu">
            <img src="/websiteimages/icons/close-icon.svg" alt="" id="close-btn" width="30px" height="30px">
            <ul class="nav-links">
                <li><a href="/index.php">Home</a></li>
                <li><a href="/services_craftsmanship.php">Services</a></li>
                <li><a href="/services_works.php">Our Works</a></li>
                <li><a href="/order.php">Order</a></li>
                <li><a href="/testimonials.php">Testimonials</a></li>
                <li><a href="/about_us.php">About Us</a></li>
                <li><a href="/contact_us.php">Contact Us</a></li>
            </ul>
        </div>
    </nav>
</header>