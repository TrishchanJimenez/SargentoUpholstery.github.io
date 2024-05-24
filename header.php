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

    if (isset($_POST['logout'])) {
        // header("Location: login.php");
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/api/logout.php');
        exit();
    }
?>
<header class="header">
    <a class="business-brand" href="/index.php">
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
                        $redirect_link = $notif['redirect_link'];
                        $notif_msg = htmlspecialchars($notif['notif_msg'], ENT_QUOTES, 'UTF-8'); // Sanitize output
                        echo "
                            <div class='notif__message $is_read' data-notif-id='{$notif['notif_id']}'>
                                <a class='notif__redirect-link' href='" . $redirect_link . "'>
                                    <span class='notif__text'>{$notif_msg}</span>
                                </a>
                                <div class='notif__divider'></div>
                                <button class='mark-read-btn' title='Mark as Read'>
                                    <svg class='mark-read-img--unread' xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#5f6368'>
                                        <path d='M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h404q-4 20-4 40t4 40H160l320 200 146-91q14 13 30.5 22.5T691-572L480-440 160-640v400h640v-324q23-5 43-14t37-22v360q0 33-23.5 56.5T800-160H160Zm0-560v480-480Zm600 80q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Z'/>
                                    </svg>
                                    <svg class='mark-read-img--read' xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#5f6368' style='display: none;'>
                                        <path d='M638-80 468-250l56-56 114 114 226-226 56 56L638-80ZM480-520l320-200H160l320 200Zm0 80L160-640v400h206l80 80H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v174l-80 80v-174L480-440Zm0 0Zm0-80Zm0 80Z'/>
                                    </svg>
                                </button>
                            </div>
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
                            <a href='/my/user_orders.php'>
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
            </div>
            <img src="/websiteimages/icons/menu-icon.svg" alt="" id="open-btn" width="36px" height="36px">
        </div>
        <div id="offcanvas-menu">
            <!-- <img src="/websiteimages/icons/close-icon-white.svg" alt="" id="close-btn" width="60px" height="60px"> -->
            <svg xmlns="http://www.w3.org/2000/svg" id="close-btn" height="60px" viewBox="0 -960 960 960" width="60px" fill="#fff"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
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