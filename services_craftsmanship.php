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
        <h2 class="intro-title"><?= $contentsById["CRAFTSHEADERTITLE"]["content_text"] ?></h2>
        <p class="intro-info"><?= $contentsById["CRAFTSHEADERTEXT"]["content_text"] ?></p>
        <a href="order.php" class="btn btn-black">Get Free Quote</a>
    </div>
    <div class="services_craftmanship_card_container">
        <div class="services_craftmanship_card">
            <img src="/websiteimages/malopit.png" alt="wood crafting" div class="services_craftmanship_card_1_image">
            <div class="services_craftmanship_card_text"> 
                <h1 class="text-gold">The Foundation of Durability</h1>
                <p>
                    Our journey into crafting superior furniture begins with 
                    the careful selection of materials, ensuring that only the 
                    finest components are used. For the sturdy frame and 
                    foundation of our pieces, we rely on treated palochina 
                    wood, a choice renowned for its remarkable durability 
                    and exceptional robustness. This wood is not just any 
                    wood; it's a testament to our dedication to creating 
                    furniture that is built to last.
                </p>
            </div>
        </div>
        <div class="services_craftmanship_card big">
            <div class="services_craftmanship_card_text ta-right"> 
                <h1 class="text-gold">Luxurious Comfort</h1>
                <p>
                    Moving on to the heart of our creations, we employ the 
                    luxuriously comfortable uratex foam. This premium foam 
                    is carefully chosen to provide a plush and supportive 
                    seating experience, allowing you to sink into your 
                    furniture while still enjoying the firm support that 
                    ensures your comfort and satisfaction for years to come. 
                </p>
            </div>
            <img src="/websiteimages/cube.png" alt="wood crafting" div class="services_craftmanship_card_2_image">
        </div>
        <div class="services_craftmanship_card small">
            <img src="/websiteimages/cube.png" alt="wood crafting" div class="services_craftmanship_card_2_image">
            <div class="services_craftmanship_card_text ta-right"> 
                <h1 class="text-gold">Luxurious Comfort</h1>
                <p>
                    Moving on to the heart of our creations, we employ the 
                    luxuriously comfortable uratex foam. This premium foam 
                    is carefully chosen to provide a plush and supportive 
                    seating experience, allowing you to sink into your 
                    furniture while still enjoying the firm support that 
                    ensures your comfort and satisfaction for years to come. 
                </p>
            </div>
        </div>
        <div class="services_craftmanship_card">
            <img src="/websiteimages/fabric cut (1).png" alt="wood crafting" div class="services_craftmanship_card_3_image">
            <div class="services_craftmanship_card_text"> 
                <h1 class="text-gold">Exceptional Fabrics</h1>
                <p>
                    But it doesn't end there. Our commitment to quality 
                    extends to the fabrics we use. We insist on utilizing only
                    the best, such as RGC fabric and German leather, both 
                    celebrated for their exceptional texture, enduring beauty,
                    and ability to withstand wear and tear gracefully. These 
                    materials not only add to the aesthetic appeal of our 
                    pieces but also ensure that your furniture retains its allure 
                    and resilience even after years of use. 
                </p>
            </div>
        </div>
    </div>
    <div class="services_craftmanship_text_2">
        <p>
            This meticulous and selective process of material sourcing and crafting guarantees that 
            each creation that bears the Sargento Upholstery name is not just a piece of furniture; it 
            is a work of art, meticulously built with a devotion to quality, beauty, and longevity. We 
            take great pride in creating furniture that not only fulfills its purpose but enriches the 
            spaces and lives it graces, turning every room into a masterpiece of comfort and 
            elegance.
        </p>
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