<?php
    require_once('database_connection.php');

    // Determine the including file's name
    $backtrace = debug_backtrace();
    $includingFile = $backtrace[0]['file'];
    $page = basename($includingFile, '.php');
    $sql = "
        SELECT 
            * 
        FROM 
            contents 
        WHERE 
            page = :page
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':page', $page);
    $stmt->execute();
    $contents = $stmt->fetchAll();
    $contentsById = [];
    foreach ($contents as $content) {
        $contentsById[$content['content_type']] = $content;
    }
?>
<link rel="stylesheet" href="/css/featured.css">
<div class="featured-banner">
    <img class="featured-banner__image" src="<?= $contentsById["FEATUREDIMG"]["image"] ?>" alt="">
    <div class="featured-banner__text">
        <h2 class="featured-banner__title"><?= stripslashes(html_entity_decode($contentsById["FEATUREDTITLE"]["content_text"])) ?></h2>
        <p class="featured-banner__description"><?= stripslashes(html_entity_decode($contentsById["FEATUREDDESC"]["content_text"])) ?></p>
        <a class="featured-banner__cta" href="/order.php">Get a Free Quote</a>
    </div>
</div>