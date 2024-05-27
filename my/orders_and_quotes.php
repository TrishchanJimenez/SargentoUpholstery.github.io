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
    <script src="/js/user_orders.js"></script>
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
            <button class="onq__tab-button">All Orders</button>
            <button class="onq__tab-button">All Quotes</button>
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
                        <th class="onq__th--corner"></th>
                        <th class="onq__th">Furniture Type</th>
                        <th class="onq__th">Service Type</th>
                        <th class="onq__th">Quantity</th>
                        <th class="onq__th">Status</th>
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
                                        <td class="onq__td--edge">' . $i . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["furniture_type"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["service_type"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["quantity"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["quote_status"]) . '</td>
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
        <!-- <div class="onq__tab onq__tab--orders">
            <?php
                $query = "
                    SELECT 
                        o.*,
                        q.quantity
                    FROM 
                        `orders` o
                            INNER JOIN
                        `quotes` q
                            USING (quote_id)
                    WHERE 
                        `user_id` = :user
                ";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <table class="onq__table">
                <thead>
                    <tr>
                        <th class="onq__th--corner"></th>
                        <th class="onq__th">Furniture Type</th>
                        <th class="onq__th">Service Type</th>
                        <th class="onq__th">Quantity</th>
                        <th class="onq__th">Status</th>
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
                                        <td class="onq__td--edge">' . $i . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["furniture_type"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["service_type"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["quantity"]) . '</td>
                                        <td class="onq__td">' . htmlspecialchars($row["quote_status"]) . '</td>
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
        </div> -->
    </div>
</body>
</html>
