<?php
    require 'database_connection.php';
    if(isset($_POST['submit-new'])) {
        $password = $_POST['new-password'];
        $id = $_POST['user-id'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password WHERE user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $is_updated = $stmt->rowCount() === 1;
        if($is_updated) {
            header("Location: changed_password.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changed Password</title>
    <link rel="stylesheet" href="css/forgot-password.css">
</head>
<body>
    <div class="container">
        <img src="/websiteimages/login-splash.jpg" alt="" class="splash-image">
        <div class="forgot-password-form">
            <img src="/websiteimages/icons/check-circle.svg" alt="" width="80px" height="80px">
            <p class="forgot-title changed-title">Password has been Reset</p>
            <a href="login.php">Login Now ></a>
        </div>
    </div>
</body>
</html>