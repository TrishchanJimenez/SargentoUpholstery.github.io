<?php
    require './database_connection.php';
    if(isset($_GET['expired'])) {
        echo "Your token has expired please place request another reset token";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot-password.css">
</head>
<body>
    <div class="container">
        <img src="/websiteimages/login-splash.jpg" alt="" class="splash-image">
        <form action="" method="post" class="forgot-password-form">
            <p class="forgot-title">Forgot Your Password?</p>
            <label for="email" class="label">Email Address</label>
            <input type="email" name="email" id="" placeholder="Enter email address" required>
            <p class="email-notif">Email Sent. Please check your inbox</p>
            <input type="submit" value="Send Reset Link" name="submitEmail" class="sendButton">
        </form>
    </div>
    <script src="js/reset_password.js"></script>
    <?php
        if(isset($_POST['submitEmail'])) {
            $email = $_POST["email"];
    
            $token = bin2hex(random_bytes(16));
    
            $token_hash = hash("sha256", $token);
    
            date_default_timezone_set('Asia/Manila');
            $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    
            $sql = "INSERT INTO reset_tokens VALUES(?, ?, ?)";
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email, $token_hash, $expiry]);
    
            if ($stmt->rowCount() > 0) {
                $mail = require __DIR__ . "/mailer.php";
    
                $mail->setFrom("noreply.sargento@gmail.com", "Sargento Upholstery");
                $mail->addAddress($email);
                $mail->Subject = "Password Reset";
                $mail->Body = <<<END
    
                Click <a href="http://sargentoupholstery.test/reset_password.php?token=$token_hash">here</a> 
                to reset your password.
    
                END;
    
                try {
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                }
            }
            echo "<script>showEmailNotif()</script>";
        }
    ?>
</body>
</html>