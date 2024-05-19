<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/works.css">
</head>
<body>
    <?php include_once("header.php") ?>
    <img src="/websiteimages/services-works-heroimage-img.jpg" alt="Sofa" div class="services-works-heroimage">
    <div class="intro-section">
        <h2 class="intro-title">Past Creations: A Showcase of Artisan Furniture</h2>
        <p class="intro-info">
            At Sargento Upholstery, we take pride in our rich history of crafting exquisite
            furniture pieces that stand the test of time. Over the years, we have had the
            privilege of working on a diverse range of projects, from elegant sofas and
            armchairs to custom-designed furniture for commercial spaces. Our portfolio
            showcases our dedication to quality craftsmanship, attention to detail, and
            timeless design. Each piece tells a story of artistry and passion, reflecting our
            commitment to creating furniture that enhances the beauty and comfort of every
            space. Explore our past works and be inspired by the legacy of Sargento Upholstery.
        </p>
        <a href="order.php" class="btn btn-black">Get Free Quote</a>
    </div>
    <div class="product-gallery">
        <div class="selector-container">
            <select name="type-selector" id="type-selector" onchange="filterGallery()">
                <option selected value="all">All</option>
                <option value="bed">Beds</option>
                <option value="armchair">Armchairs</option>
                <option value="custom">Custom</option>
                <option value="loveseat">Loveseats</option>
                <option value="ottoman">Ottomans</option>
                <option value="sofa">Sofas</option>
                <option value="cleo">Cleopatras</option>
            </select>
            <select name="color-selector" id="color-selector" onchange="filterGallery()">
                <option selected value="all">All</option>
                <option value="black">Black</option>
                <option value="blue">Blue</option>
                <option value="brown">Brown</option>
                <option value="cyan">Cyan</option>
                <option value="gray">Gray</option>
                <option value="orange">Orange</option>
                <option value="pink">Pink</option>
                <option value="purple">Purple</option>
                <option value="red">Red</option>
                <option value="white">White</option>
            </select>
        </div>
        <div class="gallery-section">
            <img src="productimages/armchair1.png" alt="" class="gallery-item brown armchair">
            <img src="productimages/armchair2.png" alt="" class="gallery-item purple armchair">
            <img src="productimages/armchair3.png" alt="" class="gallery-item black armchair">
            <img src="productimages/armchair4.png" alt="" class="gallery-item gray armchair">
            <img src="productimages/armchair5.png" alt="" class="gallery-item black armchair">
            <img src="productimages/armchair6.png" alt="" class="gallery-item black armchair">
            <img src="productimages/armchair7.png" alt="" class="gallery-item gray armchair">
            <img src="productimages/armchair8.png" alt="" class="gallery-item black armchair">
            <img src="productimages/armchair9.png" alt="" class="gallery-item pink armchair">
            <img src="productimages/armchair10.jpg" alt="" class="gallery-item brown armchair">
            <img src="productimages/armchair11.jpg" alt="" class="gallery-item gray armchair">
            <img src="productimages/armchair12.jpg" alt="" class="gallery-item brown armchair">
            
            <img src="productimages/bed1.jpg" alt="" class="gallery-item brown bed">

            <img src="productimages/chair1.png" alt="" class="gallery-item red chair">
            <img src="productimages/chair2.png" alt="" class="gallery-item white chair">

            <img src="productimages/cleopatra1.png" alt="" class="gallery-item red cleo">
            <img src="productimages/cleopatra2.png" alt="" class="gallery-item cyan cleo">
            <img src="productimages/cleopatra3.png" alt="" class="gallery-item white cleo">
            <img src="productimages/cleopatra4.png" alt="" class="gallery-item red cleo">
            <img src="productimages/cleopatra5.png" alt="" class="gallery-item pink cleo">
            <img src="productimages/cleopatra6.png" alt="" class="gallery-item white cleo">

            <img src="productimages/custom1.jpg" alt="" class="gallery-item brown custom">
            <img src="productimages/custom2.jpg" alt="" class="gallery-item white custom">
            <img src="productimages/custom3.jpg" alt="" class="gallery-item brown custom">
            <img src="productimages/custom4.jpg" alt="" class="gallery-item black custom">
            <img src="productimages/custom5.jpg" alt="" class="gallery-item brown custom">
            <img src="productimages/custom6.jpg" alt="" class="gallery-item yellow custom">
            <img src="productimages/custom7.jpg" alt="" class="gallery-item black custom">
            <img src="productimages/custom8.jpg" alt="" class="gallery-item gray custom">
            <img src="productimages/custom9.jpg" alt="" class="gallery-item white custom">
            <img src="productimages/custom10.jpg" alt="" class="gallery-item blue custom">
            
            <img src="productimages/loveseat1.png" alt="" class="gallery-item red loveseat">
            <img src="productimages/loveseat2.png" alt="" class="gallery-item gray loveseat">
            <img src="productimages/loveseat3.png" alt="" class="gallery-item red loveseat">
            <img src="productimages/loveseat4.png" alt="" class="gallery-item white loveseat">
            <img src="productimages/loveseat5.jpg" alt="" class="gallery-item brown loveseat">
            <img src="productimages/loveseat6.jpg" alt="" class="gallery-item blue loveseat">

            <img src="productimages/sofa1.png" alt="" class="gallery-item gray sofa">
            <img src="productimages/sofa2.png" alt="" class="gallery-item orange sofa">
            <img src="productimages/sofa3.png" alt="" class="gallery-item white sofa">
            <img src="productimages/sofa4.png" alt="" class="gallery-item blue sofa">
            <img src="productimages/sofa5.png" alt="" class="gallery-item white sofa">
            <img src="productimages/sofa6.png" alt="" class="gallery-item blue sofa">
            <img src="productimages/sofa7.png" alt="" class="gallery-item red sofa">
            <img src="productimages/sofa8.png" alt="" class="gallery-item blue sofa">
            <img src="productimages/sofa9.png" alt="" class="gallery-item purple sofa">
            <img src="productimages/sofa10.png" alt="" class="gallery-item white sofa">
            <img src="productimages/sofa11.png" alt="" class="gallery-item cyan white sofa">
            <img src="productimages/sofa12.png" alt="" class="gallery-item white sofa">
            <img src="productimages/sofa13.png" alt="" class="gallery-item orange sofa">
            <img src="productimages/sofa14.png" alt="" class="gallery-item black sofa">
            <img src="productimages/sofa15.png" alt="" class="gallery-item blue sofa">
            <img src="productimages/sofa16.png" alt="" class="gallery-item white sofa">
            <img src="productimages/sofa17.png" alt="" class="gallery-item cyan sofa">
            <img src="productimages/sofa18.jpg" alt="" class="gallery-item brown sofa">
            <img src="productimages/sofa19.jpg" alt="" class="gallery-item red sofa">
            <img src="productimages/sofa20.jpg" alt="" class="gallery-item black sofa">
            <img src="productimages/sofa21.jpg" alt="" class="gallery-item brown sofa">
            <img src="productimages/sofa22.jpg" alt="" class="gallery-item orange sofa">
        </div>
    </div>
    <img src="/websiteimages/Divider.png" div class="services_works_text_divider_img">
    <div class="services_works_hero_image_2">
        <div>
            <h1>
                Discover Our Artistry
            </h1>
            <a href="services_craftsmanship.php" class="btn btn-black">See More</a>
        </div>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
    <script src="js/works.js"></script>
</body>
</html>