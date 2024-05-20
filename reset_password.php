<?php
    if(!isset($_GET['token'])) {
        header("Location: login.php");
        exit();
    }

    require 'database_connection.php';
    $token = $_GET['token'];
    
    $sql = "
        SELECT
            U.user_id
        FROM users U
        JOIN reset_tokens RT ON U.email = RT.email
        WHERE 
            RT.token_hash = :token AND
            RT.expires_at > NOW()
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: forgot_password.php?expired=true");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/forgot-password.css">
</head>
<body>
    <div class="container">
        <img src="/websiteimages/login-splash.jpg" alt="" class="splash-image">
        <form action="changed_password.php" method="POST" class="forgot-password-form change-password-form">
            <p class="forgot-title">Reset Password</p>
            <input type="hidden" name="user-id" <?php "value='{$user['user_id']}'" ?>>
            <label for="new-password">
                New Password
            </label>
            <input type="password" name="new-password" id="" placeholder="Enter your new Password" required>
            <label for="new-password">
                Confirm New Password
            </label>
            <input type="password" name="confirm-password" id="" placeholder="Confirm Password" required>
            <div class="show-pass-checkbox">
                <input type="checkbox" name="show-password" id=""><span>Show Password</span>
            </div>
            <p class="error-message">Passwords Do Not Match</p>
            <input type="submit" value="Reset Password" name="submit-new" class="sendButton">
        </form>
    </div>
    <script src="js/reset_password.js"></script>
</body>
</html>