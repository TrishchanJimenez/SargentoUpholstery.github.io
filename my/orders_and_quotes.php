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
        <div class="onq__tab   onq__tab--quotes">
            <table class="onq__table   onq__table--quotes">
                <thead class="onq__thead   onq__thead--quotes">
                    <tr class="onq__tr   onq__tr--quotes">
                        <th class="onq__th   onq__th--quotes   onq__th-title" colspan="6">All Quotes</th>
                    </tr>
                </thead>
                <!-- Table headers of all quotes -->
                <thead class="onq__thead   onq__thead--quotes">
                    <tr class="onq__tr   onq__tr--quotes">
                        <th class="onq__th   onq__th--quotes   onq__tcorner">Quote ID</th>
                        <th class="onq__th   onq__th--quotes">Service Type</th>
                        <th class="onq__th   onq__th--quotes">Status</th>
                        <th class="onq__th   onq__th--quotes   onq__tcorner">View</th>
                    </tr>
                </thead>
                <tbody class="onq__tbody   onq__tbody--quotes">
                    <?php
                        // : Write the query
                        $query = "SELECT * FROM `quotes` WHERE `customer_id` = :customer_id";
                        // : Prepare the query
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                        // : Execute the query
                        $stmt->execute();
                        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // DESC: $quotes is an associative array that holds
                        if($quotes): 
                    ?>
                    <?php 
                        $i = 0;
                        foreach ($quotes as $row):
                            $i++;
                    ?>
                        <tr>
                            <td class="onq__td   onq__td--quote"> <?= htmlspecialchars($row["quote_id"]) ?> </td>
                            <td class="onq__td   onq__td--quote"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair"))?> </td>
                            <td class="onq__td   onq__td--quote"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["quote_status"] ?? 'N/A')))?> </td>
                            <td class="onq__td   onq__td--edge">
                                <a href="quotes.php?quote_id=<?= htmlspecialchars($row["quote_id"])?> ">
                                    >
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php endif; ?>
                    <tr class="onq__tr">
                        <td class="onq__td--end" colspan="6">End of records</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="onq__tab   onq__tab--orders">
            <table class="onq__table">
                <thead class="onq__thead   onq__thead--orders">
                    <tr class="onq__tr   onq__tr--orders">
                        <th class="onq__th    onq__th--order    onq__th--title" colspan="8">All Orders</th>
                    </tr>
                </thead>
                <thead class="onq__thead   onq__thead--orders">
                    <tr>
                        <th class="onq__th   onq__th--order   onq__th--corner">Order ID</th>
                        <th class="onq__th   onq__th--order">Furniture Type</th>
                        <th class="onq__th   onq__th--order">Service Type</th>
                        <th class="onq__th   onq__th--order">Quantity</th>
                        <th class="onq__th   onq__th--order">Delivery Method</th>
                        <th class="onq__th   onq__th--order">Price</th>
                        <th class="onq__th   onq__th--order">Status</th>
                        <th class="onq__th   onq__th--order   onq__th--corner"></th>
                    </tr>
                </thead>
                <tbody class="onq__tbody   onq__tbody--orders">
                    <?php
                        $query = "
                            SELECT 
                                *
                            FROM 
                                `orders` o
                                    INNER JOIN
                                `quotes` q ON o.quote_id = q.quote_id
                                    INNER JOIN
                                `items` i ON q.quote_id = i.quote_id
                            WHERE 
                                q.customer_id = :customer_id
                            ORDER BY
                                q.updated_at DESC
                        ";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':customer_id', $user_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if($orders):
                    ?>
                    <?php
                        $i = 0;
                        foreach ($orders as $row):
                    ?>
                    <tr>
                        <td class="onq__td"> <?= htmlspecialchars($row["order_id"]) ?> </td>
                        <td class="onq__td onq__td--alt onq__td--order"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["furniture"] ?? 'N/A'))) ?> </td>
                        <td class="onq__td onq__td--order"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["service_type"] ?? 'N/A') == "mto" ? "Made-To-Order" : "Repair")) ?> </td>
                        <td class="onq__td onq__td--alt onq__td--order"> <?= htmlspecialchars($row["quantity"] ?? 'N/A') ?>  item/s</td>
                        <td class="onq__td onq__td--order"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["del_method"] ?? 'N/A'))) ?> </td>
                        <td class="onq__td onq__td--alt onq__td--order"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["quoted_price"] ?? 'N/A'))) ?> </td>
                        <td class="onq__td onq__td--order"> <?= ucwords(str_replace('_', ' ', htmlspecialchars($row["order_status"] ?? 'N/A'))) ?> </td>
                        <td class="onq__td--edge">
                            <a href="orders.php?order_id= <?= htmlspecialchars($row["order_id"]) ?> ">
                                >
                            </a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    <?php endif; ?>
                    <tr class="onq__tr">
                        <td class="onq__td--end" colspan="8">End of records</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="/js/globals.js"></script>
</body>
</html>
