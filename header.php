<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
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
            <img src="/websiteimages/icons/bell-icon.svg" alt="" id="notif-btn" width="36px" height="36px">
            <?php
                if(isset($_SESSION['user_id'])) {
                    echo "<img src='/websiteimages/icons/account-icon.svg' alt='' id='account-btn' width='36px' height='36px'>";
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
                    if($_SESSION['access_type'] === "admin") {
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
                    if(isset($_POST['logout'])) {
                        session_destroy();
                        header("Location: ".$_SERVER['PHP_SELF']);
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