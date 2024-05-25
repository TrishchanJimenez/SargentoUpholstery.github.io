<?php 
    require_once('database_connection.php');

    // Determine the including file's name
    $backtrace = debug_backtrace();
    $includingFile = $backtrace[0]['file'];
    $page = basename($includingFile, '.php');

    $query = "
        SELECT 
            * 
        FROM 
            contents 
        WHERE 
            page = :page
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':page', $page);
    $stmt->execute();
    $outroContents = $stmt->fetchAll();
    $outroContentsById = [];
    foreach ($outroContents as $outroContent) {
        $outroContentsById[$outroContent['content_type']] = $outroContent;
    }

    // Default value for $needs_cta if it's not set in the including file
    if (!isset($needs_cta)) {
        $needs_cta = true;
    }
?>
<link rel="stylesheet" href="/css/outro.css">
<link rel="stylesheet" href="/css/global.css">
<div class="outro-banner">
    <img class="outro-banner__image" src="<?= $outroContentsById["OUTRO_IMG"]["image"] ?>" alt="">
    <div class="outro-banner__overlay">
        <div class="outro-banner__text">
            <h2 class="outro-banner__title"><?= stripslashes(html_entity_decode($outroContentsById["OUTRO_TITLE"]["content_text"])) ?></h2>
            <a class="outro-banner__cta" href="<?= stripslashes(html_entity_decode($outroContentsById["OUTRO_CTALINK"]["content_text"])) ?>">
                <?= stripslashes(html_entity_decode($outroContentsById["OUTRO_CTATEXT"]["content_text"])) ?>
            </a>
        </div>
    </div>
</div>