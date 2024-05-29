<?php
    require '../database_connection.php';
    session_start();
    if(!isset($_SESSION['access_type']) || $_SESSION['access_type'] === "customer"){
        header("Location: ../index.php");
        exit();
    }

    $quotationSql = "
        SELECT 
            Q.quote_id, 
            U.name, 
            U.email, 
            q.service_type AS type, 
            GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(I.furniture, 1, 1)), LOWER(SUBSTRING(I.furniture, 2))) SEPARATOR ', ') AS item,
            q.created_at AS placement_date, 
            q.quote_status AS status
        FROM 
            quotes Q
        JOIN
            users U ON U.user_id = Q.customer_id
        LEFT JOIN
            items I USING(quote_id)
        WHERE
            Q.quote_status = 'pending'
            OR Q.quote_status = 'approved'
        GROUP BY 
            Q.quote_id
        ORDER BY
            Q.quote_id DESC
    ";

    $stmt = $conn->prepare($quotationSql);
    $stmt->execute();
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotations</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/admin/quotations.css">
</head>
<body>
    <div class="orders">
        <?php require 'sidebar.php' ?>
        <div class="order-list">
            <p class="main-title">Request For Quotes</p>
            <hr class="divider">
            <form class="order-filters" method="get" action="">
                <div class="filter-type selector-container">
                    <select name="order-type" id="" class="selector">
                        <option value="default">Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
            </form>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Quote Id</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Item(s)</th>
                        <th>Type</th>
                        <th>Placement Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($quotations AS $quote) {
                            $date = date('M d, Y', strtotime($quote['placement_date']));
                            $type = ($quote['type'] === "mto") ? "MTO" : "Repair";
                            $status = $quote['status'];
                            $status_text = ($status === "pending") ? "Pending" : "Approved";
                            // $item = ucfirst($quote['item']);
                            echo "
                            <tr data-id='{$quote['quote_id']}'>
                                <td>{$quote['quote_id']}</td>
                                <td>{$quote['name']}</td>
                                <td>{$quote['email']}</td>
                                <td>{$quote['item']}</td>
                                <td>{$type}</td>
                                <td>{$date}</td>
                                <td class='prod-status status'>
                                    <span data-prod-status='{$status}'>{$status_text}</span>
                                </td>
                                <td class='chevron-right'>
                                    <a href='quotation_details.php?quote-id={$quote['quote_id']}'>
                                        <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#6B7280'><path d='M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z'/></svg>
                                    </a>
                                </td>
                            </tr>
                            ";  
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>