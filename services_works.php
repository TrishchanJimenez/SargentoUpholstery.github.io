<?php
    require 'database_connection.php';
?>
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
    <?php 
        /* include_once("header.php");
        $sql = "SELECT * FROM contents WHERE page = 'services_works'";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        } */
    ?>
    <?php 
        require_once('header.php');
        $needs_cta = true;
        require_once('intro.php');
    ?>
    <!-- <img src="/websiteimages/services-works-heroimage-img.jpg" alt="Sofa" div class="services-works-heroimage">
    <div class="intro-section">
        <h2 class="intro-title editable short-text" data-id="WORKSHEADERTITLE"> /* stripslashes(htmlspecialchars_decode($contentsById["WORKSHEADERTITLE"]["content_text"])) */</h2>
        <p class="intro-info editable long-text" data-id="WORKSHEADERTEXT"> /* stripslashes(htmlspecialchars_decode($contentsById["WORKSHEADERTEXT"]["content_text"])) */</p>
        <a href="order.php" class="btn btn-black">Get Free Quote</a>
    </div> -->
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
                <option value="cleopatra">Cleopatras</option>
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
            <?php
                $query = "
                    SELECT
                        *
                    FROM 
                        works
                    ORDER BY 
                        works_id 
                            DESC
                ";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $works) {
                    // Access the values of each row here
                    // For example, you can access the "category" and "color" columns like this:
                    $category = $works['category'];
                    $color = $works['color'];
                    $path = $works['img_path'];
                    // Use the values to display the gallery items
                    echo "
                        <img src='{$path}' class='gallery-item' data-color='{$color}' data-category='{$category}'>
                    ";
                }
            ?>
        </div>
    </div>
    <img src="/websiteimages/Divider.png" div class="services_works_text_divider_img">
    <?php 
        require_once('outro.php');
        require_once('footer.php'); 
    ?>
    <script src="js/globals.js"></script>
    <script src="js/works.js"></script>
</body>
</html>