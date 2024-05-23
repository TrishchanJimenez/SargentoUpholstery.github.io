<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/craftsmanship.css">
</head>
<body>
    <?php include_once("header.php") ?>
    <?php
        require 'database_connection.php';
        $sql = "SELECT * FROM contents WHERE page = 'services_craftsmanship'";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <img src="/websiteimages/2mensofa.png" alt="Photo of 2 men carrying a Sofa" class="intro-image">
    <div class="intro-section">
        <h2 class="intro-title"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSHEADERTITLE"]["content_text"])) ?></h2>
        <p class="intro-info"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSHEADERTEXT"]["content_text"])) ?></p>
        <a href="order.php" class="btn btn-black">Get Free Quote</a>
    </div>
    <div class="services_craftmanship_card_container">
        <div class="services_craftmanship_card">
            <img src="/websiteimages/malopit.png" alt="wood crafting" div class="services_craftmanship_card_1_image">
            <div class="services_craftmanship_card_text"> 
                <h1 class="text-gold"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE1"]["content_text"])) ?></h1>
                <p><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT1"]["content_text"])) ?></p>
            </div>
        </div>
        <div class="services_craftmanship_card big">
            <div class="services_craftmanship_card_text ta-right"> 
                <h1 class="text-gold"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE2"]["content_text"])) ?></h1>
                <p><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT2"]["content_text"])) ?></p>
            </div>
            <img src="/websiteimages/cube.png" alt="wood crafting" div class="services_craftmanship_card_2_image">
        </div>
        <div class="services_craftmanship_card small">
            <img src="/websiteimages/cube.png" alt="wood crafting" div class="services_craftmanship_card_2_image">
            <div class="services_craftmanship_card_text ta-right"> 
                <h1 class="text-gold"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE2"]["content_text"])) ?></h1>
                <p><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT2"]["content_text"])) ?></p>
            </div>
        </div>
        <div class="services_craftmanship_card">
            <img src="/websiteimages/fabric cut (1).png" alt="wood crafting" div class="services_craftmanship_card_3_image">
            <div class="services_craftmanship_card_text"> 
                <h1 class="text-gold"><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTITLE3"]["content_text"])) ?></h1>
                <p><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSCARDTEXT3"]["content_text"])) ?></p>
            </div>
        </div>
    </div>
    <div class="services_craftmanship_text_2">
        <p><?= stripslashes(htmlspecialchars_decode($contentsById["CRAFTSFOOTERTEXT"]["content_text"])) ?></p>
    </div>
    <img src="/websiteimages/Divider.png" div class="services_craftmanship_text_divider_img">
    <div class="services_craftmanship_hero_image_2">
        <h1>
            Explore Our Masterpieces
        </h1>
        <a href="services_works.php" class="btn btn-black">See More</a>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>
</html>