<?php
    require 'database_connection.php';
    if(isset($_POST['new-password'])) {
        $user_id = $_POST['user-id'];
        $password = $_POST['new-password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changed Password</title>
</head>
<body>
    <h1>Your password has been changed</h1>
</body>
</html>