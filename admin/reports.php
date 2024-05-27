<?php
require_once '../database_connection.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['access_type'] !== 'admin') {
    header('Location: ../login.php');
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
    <link rel="stylesheet" href="/css/reports.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="reports">
        <?php require 'sidebar.php' ?>
        <div class="report-content">
            <p class="main-title">Reports</p>
            <hr class="divider">
            <div class="reports-selector">
                
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/dashboard_test.js"></script>
</body>
</html>