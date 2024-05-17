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
    <form action="changed_password.php" method="POST" class="forgot-password-form change-password-form">
        <p class="forgot-title">Set your New Password</p>
        <input type="hidden" name="user-id" <?= "value='{$user['user_id']}'" ?>>
        <label for="new-password">
            New Password
        </label>
        <input type="password" name="new-password" id="" placeholder="Enter your new Password">
        <label for="new-password">
            Confirm New Password
        </label>
        <input type="password" name="confirm-password" id="" placeholder="Confirm Password">
        <div class="show-pass-checkbox">
            <input type="checkbox" name="show-password" id=""><span>Show Password</span>
        </div>
        <input type="submit" value="Reset" name="submit-new" class="sendButton">
    </form>
    <?php
        if(isset($_POST['submit-new'])) {
            $password = $_POST['new-password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $is_updated = $stmt->rowCount() === 1;
            echo $is_updated;
            // if($is_updated) echo "Password Changed";
        }
    ?>
    <script src="js/reset_password.js"></script>
</body>
</html>