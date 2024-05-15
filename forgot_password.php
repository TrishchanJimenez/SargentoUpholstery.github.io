<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <form action="" method="post">
        <input type="email" name="email" id="">
        <input type="submit" value="Send" name="submitEmail">
    </form>
    <?php
        require './database_connection.php';
        
        if(isset($_POST['submitEmail'])) {
            $email = $_POST["email"];
    
            $token = bin2hex(random_bytes(16));
    
            $token_hash = hash("sha256", $token);
    
            $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    
            $sql = "INSERT INTO reset_tokens VALUES(?, ?, ?)";
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email, $token_hash, $expiry]);
    
            if ($stmt->rowCount() > 0) {
                $mail = require __DIR__ . "/mailer.php";
    
                $mail->setFrom("trishchanjimenez@gmail.com");
                $mail->addAddress($email);
                $mail->Subject = "Password Reset";
                $mail->Body = <<<END
    
                Click <a href="http://sargentoupholstery.test/reset_password.php?token=$token">here</a> 
                to reset your password.
    
                END;
    
                try {
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                }
            }
            echo "Message sent, please check your inbox.";
        }
    ?>
</body>
</html>