<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/contacts.css">
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
                page = 'contact_us'
        ";
        $stmt = $conn->query($sql);
        $contents = $stmt->fetchAll();
        $contentsById = [];
        foreach ($contents as $content) {
            $contentsById[$content['content_id']] = $content;
        }
    ?>
    <div class="contact_us_text">
        <div class="contact_info">
            <h1> Cellphone Number </h1>
            <p><?= stripslashes(htmlspecialchars_decode($contentsById["CPNNUM"]["content_text"])) ?></p>
            <h1> Mailing Adress </h1>
            <p><?= stripslashes(htmlspecialchars_decode($contentsById["MAILADD"]["content_text"])) ?></p>
        </div>
        <div class="adresses">
            <h1>Business Address</h1>
            <p><?= stripslashes(htmlspecialchars_decode($contentsById["BUSIADD"]["content_text"])) ?></p>
            <h1>Business Hours</h1>
            <p><?= stripslashes(htmlspecialchars_decode($contentsById["BUSIHRS"]["content_text"])) ?></p>
        </div>
    </div>
    <div class="map">
        <p><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.7812381749773!2d121.04867737510415!3d14.49724408597688!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf89d9ee44f5%3A0xf2d0bac3ab591fb7!2sSargento%20Upholstery%20workshop!5e0!3m2!1sen!2sph!4v1700925157210!5m2!1sen!2sph" 
            width="1031" height="565" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
    </div>
    <?php include_once("footer.php") ?>
    <script src="js/globals.js"></script>
</body>
</html>