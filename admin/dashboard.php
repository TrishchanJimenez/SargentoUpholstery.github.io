<?php
    require '../database_connection.php';
    session_start();
    if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin") {
        header("Location: ../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/global.css">
</head>
<body>
    <?php require 'sidebar.php' ?>
</body>
</html>