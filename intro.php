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
    $introContents = $stmt->fetchAll();
    $introContentsById = [];
    foreach ($introContents as $introContent) {
        $introContentsById[$introContent['content_type']] = $introContent;
    }

    // Default value for $needs_cta if it's not set in the including file
    if (!isset($needs_cta)) {
        $needs_cta = true;
    }
?>
<link rel="stylesheet" href="/css/intro.css">
<link rel="stylesheet" href="/css/global.css">
<div class="intro-banner">
    <div class="intro-banner__overlay">
        <div class="intro-banner__text">
            <h2 class="intro-banner__title"><?= stripslashes(html_entity_decode($introContentsById["INTRO_TITLE"]["content_text"])) ?></h2>
            <?php if (isset($introContentsById["INTRO_DESC"]["content_text"])): ?>
                <p class="intro-banner__description"><?= stripslashes(html_entity_decode($introContentsById["INTRO_DESC"]["content_text"])) ?></p>
            <?php endif; ?>
            <?php if ($needs_cta): ?>
                <a class="intro-banner__cta" href="/quote.php">Get a Free Quote</a>
            <?php endif; ?>
        </div>
    </div>
    <img class="intro-banner__image" src="<?= $introContentsById["INTRO_IMG"]["image"] ?>" alt="">
</div>