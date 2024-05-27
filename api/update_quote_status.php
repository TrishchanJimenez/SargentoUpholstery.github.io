<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit;
    }
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['quote_id']) || !isset($_POST['status'])) {
        http_response_code(400);
        exit;
    }

    $quote_id = htmlspecialchars($_POST['quote_id']);
    $status = htmlspecialchars($_POST['status']);

    require_once('../database_connection.php');

    $query = "
        UPDATE 
            `quotes` 
        SET
            `quote_status` = :status 
        WHERE 
            `quote_id` = :quote_id 
                AND 
            `customer_id` = :customer_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':quote_id', $quote_id, PDO::PARAM_INT);
    $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
?>