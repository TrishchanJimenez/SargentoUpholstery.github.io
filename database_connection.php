<?php
    $host = 'localhost';
    $db_name = 'sargento';
    $username = 'root';
    $password = '';
    $conn;

    try {
        $conn = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
    }
?>