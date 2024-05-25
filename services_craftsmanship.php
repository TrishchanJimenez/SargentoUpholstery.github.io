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
    <?php 
        $needs_cta = true;
        require_once('intro.php');
    ?>
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
    <?php 
        require_once('outro.php');
        require_once('footer.php');
    ?>
    <script src="js/globals.js"></script>
</body>
</html>