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
        require_once('database_connection.php');
        require_once('header.php');
        $needs_cta = false;
        require_once('intro.php');
        
        $sql = "
            SELECT 
                * 
            FROM 
                contents 
            WHERE 
                page = 'about_us'
        ";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <div class="about-us-history about-us-container">
        <h2 class="text-gold short-text editable" data-id="ABOUTHISTORYTITLE"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTHISTORYTITLE"]["content_text"]))?></h2>
        <p class="long-text editable" data-id="ABOUTHISTORYTEXT"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTHISTORYTEXT"]["content_text"]))?></p>
    </div>
    <div class="values-philosophy about-us-container">
        <h2 class="long-text editable" data-id="ABOUTVALUESTITLE"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESTITLE"]["content_text"]))?></h2>
        <div class="values-container">
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING1"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING1"]["content_text"]))?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT1"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT1"]["content_text"]))?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING2"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING2"]["content_text"]))?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT2"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT2"]["content_text"]))?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING3"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING3"]["content_text"]))?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT3"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT3"]["content_text"]))?></p>
            </div>
            <div class="values">
                <img src="" alt="" class="values-logo">
                <h3 class="editable short-text" data-id="ABOUTVALUESSUBHEADING4"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBHEADING4"]["content_text"]))?></h3>
                <p class="editable long-text" data-id="ABOUTVALUESSUBTEXT4"><?=stripslashes(htmlspecialchars_decode($contentsById["ABOUTVALUESSUBTEXT4"]["content_text"]))?></p>
            </div>
        </div>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>
</html>