<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php
        include_once("header.php");
        require 'database_connection.php';
        $sql = "SELECT * FROM contents WHERE page = 'home'";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <div class="featured">
        <h2 class="featured-title">
            Well-crafted & Timeless
            <span>We Bring Your Vision to Life: Crafting Excellence in Every Design</span>
        </h2>
        <a href="order.php" class="btn btn-black">Get a free quote</a>
    </div>
    <div class="product">
        <h2 class="product-category-title"><?= $contentsById["HOMECRAFTSTITLE"]["content_text"] ?></h2>
        <p class="product-category-intro"><?= $contentsById["HOMECRAFTSTEXT"]["content_text"] ?></p>
        <div class="product-categories">
            <a class="product-category" id="category-loveseat">
                <img src="/websiteimages/loveseatsimg.png" alt="">
                <p>Loveseats</p>
            </a>
            <a class="product-category" id="category-sofa">
                <img src="/websiteimages/sofasimg.png" alt="">
                <p>Sofas</p>
            </a>
            <a class="product-category" id="category-bed">
                <img src="/websiteimages/bedsimage.png" alt="">
                <p>Beds</p>
            </a>
            <a class="product-category" id="category-armchair">
                <img src="/websiteimages/chairsimg.png" alt="">
                <p>Chairs</p>
            </a>
            <a class="product-category" id="category-ottoman">
                <img src="/websiteimages/ottomanimg.png" alt="">
                <p>Ottomans</p>
            </a>
            <a class="product-category" id="category-custom">
                <img src="/websiteimages/customordersimg.png" alt="">
                <p>Custom</p>
            </a>
        </div>
    </div>
    <div class="testimonials">
        <h2 class="testimonials-title">Our Clients' Testimonials</h2>
        <div class="reviews">
            <div class="review">
                <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
                <p>Very responsive, Good and trustworthy. Plus the quality of their work is pretty good! Two-thumbs up for
                    Sargento Upholstery.<br><br><span class="client-name">-JB von Kampfer</span></p>
                <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
            </div>
            <div class="review">
                <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
                <p>Quality at pangmatagalan talaga ang gawa ng Sargento Upholstery. Kudos!!<br><br><span
                        class="client-name">-Albert Mendoza</span></p>
                <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
            </div>
        </div>
        <a href="testimonials.php" class="btn btn-black">More Reviews</a>
    </div>
    <div class="about-us">
        <img src="/websiteimages/teampicture.png" alt="" class="team-picture">
        <h2 class="text-gold"><?= $contentsById["HOMEABOUTTITLE"]["content_text"] ?></h2>
        <p class="business-history"><?= $contentsById["HOMEABOUTTEXT"]["content_text"] ?></p>
        <a href="about_us.php" class="btn btn-white">Learn More</a>
    </div>
    <?php include_once("chat.php") ?>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
    <script src="js/productlink.js"></script>
</body>
</html>
