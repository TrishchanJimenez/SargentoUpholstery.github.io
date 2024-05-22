<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/about.css">
</head>
<body>
    <?php
        include_once("header.php");
        require 'database_connection.php';
        $sql = "SELECT * FROM contents WHERE page = 'about_us'";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <img src="/websiteimages/teampicture.png" alt="" class="about-us-featuredimg">
    <div class="about-us-container">
        <h2 class="editable short-text" data-id="ABOUTHEADERTITLE"><?= $contentsById["ABOUTHEADERTITLE"]["content_text"] ?></h2>
        <p class="editable long-text" data-id="ABOUTHEADERTEXT"><?= $contentsById["ABOUTHEADERTEXT"]["content_text"] ?></p>
        <a href="order.php" class="btn btn-black">Get a Free Quote</a>
    </div>
    <div class="about-us-history about-us-container">
        <h2 class="text-gold short-text editable" data-id="ABOUTHISTORYTITLE"><?=$contentsById["ABOUTHISTORYTITLE"]["content_text"]?></h2>
        <p class="long-text editable" data-id="ABOUTHISTORYTEXT"><?=$contentsById["ABOUTHISTORYTEXT"]["content_text"]?></p>
    </div>
    <div class="values-philosophy about-us-container">
        <h2 class="long-text editable" data-id="ABOUTVALUESTITLE"><?=$contentsById["ABOUTVALUESTITLE"]["content_text"]?></h2>
        <div class="values-container">
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING1"><?=$contentsById["ABOUTVALUESSUBHEADING1"]["content_text"]?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT1"><?=$contentsById["ABOUTVALUESSUBTEXT1"]["content_text"]?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING2"><?=$contentsById["ABOUTVALUESSUBHEADING2"]["content_text"]?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT2"><?=$contentsById["ABOUTVALUESSUBTEXT2"]["content_text"]?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING3"><?=$contentsById["ABOUTVALUESSUBHEADING3"]["content_text"]?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT3"><?=$contentsById["ABOUTVALUESSUBTEXT3"]["content_text"]?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING4"><?=$contentsById["ABOUTVALUESSUBHEADING4"]["content_text"]?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT4"><?=$contentsById["ABOUTVALUESSUBTEXT4"]["content_text"]?></p>
            </div>
        </div>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>
</html>