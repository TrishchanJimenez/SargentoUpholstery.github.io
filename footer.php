<footer class="footer">
    <div class="footer-title d-flex">
        <div class="f-title">
            <h3 class="text-gold  main-title">Sargento Upholstery</h3>
            <h3 class=" sub-title">Textile Furniture Service</h3>
        </div>
    </div>
    <div class="socials">
        <h3>Socials</h3>
        <div class="img-container d-flex">
            <a href="mailto:placeholder@test.com"><img src="/websiteimages/icons/email-icon.svg" alt="" class="email-icon"></a>     
            <a href="https://www.facebook.com/SargentoUpholstery"><img src="/websiteimages/icons/facebook_logo.svg" alt=""></a>
            <a href="https://www.facebook.com/SargentoUpholstery"><img src="/websiteimages/icons/messenger_logo.svg" alt=""></a>
        </div>
    </div>
    <div class="quicklinks">
        <h3>Quicklinks</h3>
        <div class="link-container">
            <a href="services_craftsmanship.php">Services</a>
            <a href="services_works.php">Our Works</a>
            <a href="/quote.php">Request Quote</a>
            <a href="testimonials.php">Testimonials</a>
            <a href="about_us.php">About Us</a>
            <a href="contact_us.php">Contact Us</a>
        </div>
    </div>
</footer>
<?php
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id']) && $_SESSION['access_type'] == 'customer') {
        include_once("chat.php");
    }
?>