<?php
    require '../database_connection.php';
    session_start();
    if(!isset($_SESSION['access_type']) || $_SESSION['access_type'] === "customer"){
        header("Location: ../index.php");
        exit();
    }

    $quotationSql = "
        SELECT
            quote_id,
            furniture_type AS item,
            name AS customer_name,
            email,
            service_type AS type,
            quantity,
            quote_status AS status,
            created_at AS placement_date
        FROM
            quotes Q
        JOIN users O ON Q.customer_id = O.user_id  
        WHERE
            quote_status IN ('pending', 'approved')
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
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Quote Id</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Item</th>
                        <th>Quantity</th>
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
                            $item = ucfirst($quote['item']);
                            echo "
                            <tr data-id='{$quote['quote_id']}'>
                                <td>{$quote['quote_id']}</td>
                                <td>{$quote['customer_name']}</td>
                                <td>{$quote['email']}</td>
                                <td>{$item}</td>
                                <td>{$quote['quantity']}</td>
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