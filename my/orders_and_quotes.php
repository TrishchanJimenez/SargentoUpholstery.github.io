<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/orders_and_quotes.css">
    <script src="/js/my/orders_and_quotes.js"></script>
    <title>Sargento Upholstery</title>
</head>

<body>
    <?php 
        require_once('../database_connection.php');
        require_once('../header.php'); 
    ?>
    <div class="onq">
        <h1 class="onq__title">My Orders and Quotes</h1>
        <div class="onq__tab-button-container">
            <button class="onq__tab-button onq__tab-button--quotes">All Quotes</button>
            <button class="onq__tab-button onq__tab-button--orders">All Orders</button>
        </div>
        <div class="onq__tab onq__tab--quotes">
            <?php
                $query = "SELECT * FROM `quotes` WHERE `customer_id` = :customer_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th onq__th--quote onq__th--title" colspan="6">All Quotes</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="onq__th--corner">Quote ID</th>
                        <th class="onq__th onq__th--quote">Furniture Type</th>
                        <th class="onq__th onq__th--quote">Service Type</th>
                        <th class="onq__th onq__th--quote">Quantity</th>
                        <th class="onq__th onq__th--quote">Status</th>
                        <th class="onq__th--corner"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($quotes) {
                            $i = 0;
                            foreach ($quotes as $row) {
                                $i++;
                                echo '
                                    <tr>
                                        <td class="onq__td onq__td--quote">' . htmlspecialchars($row["quote_id"]) . '</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture_type"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                        <td class="onq__td onq__td--quote">' . htmlspecialchars($row["quantity"] ?? 'N/A') . ' item/s</td>
                                        <td class="onq__td onq__td--quote">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["quote_status"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td--edge">
                                            <a href="quotes.php?quote_id=' . htmlspecialchars($row["quote_id"]) . '">
                                                >
                                            </a>
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                        echo '
                            <tr class="onq__tr">
                                <td class="onq__td--end" colspan="6">End of records</td>
                            </tr>
                        ';
                    ?>
                </tbody>
            </table>
        </div>
        <div class="onq__tab onq__tab--orders">
            <?php
                $query = "
                    SELECT 
                        *
                    FROM 
                        `orders` o
                            INNER JOIN
                        `quotes` q
                            USING (quote_id)
                    WHERE 
                        `user_id` = :user_id
                ";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th onq__th--order onq__th--title" colspan="8">All Orders</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="onq__th--corner">Order ID</th>
                        <th class="onq__th onq__th--order">Furniture Type</th>
                        <th class="onq__th onq__th--order">Service Type</th>
                        <th class="onq__th onq__th--order">Quantity</th>
                        <th class="onq__th onq__th--order">Delivery Method</th>
                        <th class="onq__th onq__th--order">Price</th>
                        <th class="onq__th onq__th--order">Status</th>
                        <th class="onq__th--corner"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($orders) {
                            $i = 0;
                            foreach ($orders as $row) {
                                $i++;
                                echo '
                                    <tr>
                                        <td class="onq__td--edge">' . htmlspecialchars($row["order_id"]) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture_type"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) . '</td>
                                        <td class="onq__td onq__td--order">' . htmlspecialchars($row["quantity"] ?? 'N/A') . ' item/s</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["del_method"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["quoted_price"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td onq__td--order">' . ucwords(str_replace('_', ' ', htmlspecialchars($row["order_status"] ?? 'N/A'))) . '</td>
                                        <td class="onq__td--edge">
                                            <a href="orders.php?order_id=' . htmlspecialchars($row["order_id"]) . '">
                                                >
                                            </a>
                                        </td>
                                    </tr>
                                ';
                            }
                        }
                        echo '
                            <tr class="onq__tr">
                                <td class="onq__td--end" colspan="8">End of records</td>
                            </tr>
                        ';
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
