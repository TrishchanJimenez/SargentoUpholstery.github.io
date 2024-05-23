<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/testimonials.css">
</head>
<body>
    <?php
        include_once("header.php");
        require 'database_connection.php';
        $sql = "SELECT * FROM contents WHERE page = 'testimonials'";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <img src="/websiteimages/testimonialsfeaturedimg.png" alt="" class="testimonials-featured-img">
    <div class="testimonials-title">
        <h2><?= stripslashes(html_entity_decode($contentsById["TESTIMONIALHEADERTITLE"]["content_text"])) ?></h2>
        <p><?= stripslashes(html_entity_decode($contentsById["TESTIMONIALHEADERSUBTEXT"]["content_text"])) ?></p>
        <a href="order.php">Get a Free Quote</a>
    </div>
    <img src="/websiteimages/Divider.png" class="testimonials-divider">
    <h2 class="star-rating">Our Average Star Rating</h2>
    <div class="star-container">
        <img src="/websiteimages/starimg.png" alt="">
        <img src="/websiteimages/starimg.png" alt="">
        <img src="/websiteimages/starimg.png" alt="">
        <img src="/websiteimages/starimg.png" alt="">
        <img src="/websiteimages/starimg.png" alt="">
    </div>
    <div class="review-container">
        <div class="review">
            <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
            <p>"Thank you po ang Ganda ng Sofa."<br><br><span class="client-name">-Lenard</span></p>
            <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
        </div>
        <div class="review">
            <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
            <p>"Maraming ty. Ang ganda."<br><br><span class="client-name">-Naej</span></p>
            <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
        </div>
        <div class="review">
            <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
            <p>"Thank you. Solid yung gawa , Malinis and quality."<br><br><span class="client-name">-Keith</span></p>
            <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
        </div>
        <div class="review">
            <img src="/websiteimages/Start quote.png" alt="" class="quote quote-start">
            <p>"Maam thank you po, Happy costumer po ako, sa uulitin po."<br><br><span class="client-name">-Layla</span></p>
            <img src="/websiteimages/End quote.png" alt="" class="quote quote-end">
        </div>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>
</html>