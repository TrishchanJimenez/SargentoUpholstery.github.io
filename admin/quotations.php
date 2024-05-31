<?php
    require '../database_connection.php';
    session_start();
    if(!isset($_SESSION['access_type']) || $_SESSION['access_type'] === "customer"){
        header("Location: ../index.php");
        exit();
    }

    $status_filter = isset($_GET['quote-status']) ? $_GET['quote-status'] : 'default';
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    // echo $status_filter;
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
        GROUP BY 
            Q.quote_id
    ";

    if($status_filter !== 'default') {
        // echo "test1";
        $quotationSql .= " HAVING status = :status_filter ";
    } else {
        $quotationSql .= "
            HAVING status = 'pending'
            OR status = 'approved'
            OR status = 'rejected'
            OR status = 'cancelled'
        ";
    }

    $quotationSql .= " 
        ORDER BY Q.quote_id DESC
        LIMIT 10
        OFFSET :offset
    ";

    // echo $quotationSql;
    $stmt = $conn->prepare($quotationSql);
    if($status_filter !== 'default') {
        // echo "test2";
        $stmt->bindParam(':status_filter', $status_filter);
    }
    $offset = ($current_page - 1) * 10;

    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countSql = "
        SELECT 
            COUNT(DISTINCT Q.quote_id) as total
        FROM 
            quotes Q
    ";

    if($status_filter !== 'default') {
        $countSql .= " WHERE quote_status = :status_filter ";
    } else {
        $countSql .= "
            WHERE quote_status IN ('pending', 'approved', 'rejected', 'cancelled')
        ";
    }

    $countStmt = $conn->prepare($countSql);
    if($status_filter !== 'default') {
        $countStmt->bindParam(':status_filter', $status_filter);
    }

    $countStmt->execute();
    $total_records = $countStmt->fetchColumn();
    $page_count = ceil($total_records / 10);
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
                    <select name="quote-status" id="" class="selector">
                        <option value="default">Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
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
                            $status_text = ucfirst($status);

                            $item = ucwords($quote['item']);
                            if (strlen($item) > 12) {
                                $item = substr($item, 0, 12) . '...';

                                $item_display = "
                                    <span class='item-display'>
                                        {$quote['item']}
                                    </span>
                                ";
                            } else {
                                $item_display = "";
                            }
                            // $item = ucfirst($quote['item']);
                            echo "
                            <tr data-id='{$quote['quote_id']}'>
                                <td>{$quote['quote_id']}</td>
                                <td>{$quote['name']}</td>
                                <td>{$quote['email']}</td>
                                <td class='item'>
                                    {$item}
                                    {$item_display}
                                </td>
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
            <hr class="divider">
            <div class="query-records">
                <div class="record-count">
                    Showing 
                    <span>
                        <?php
                            if($total_records === 0) echo 0;
                            else echo (($current_page-1) * 10) + 1;
                        ?>
                    </span>
                    to 
                    <span>
                    <?php 
                        if($current_page * 10 > $total_records) echo $total_records;
                        else echo $current_page * 10;
                    ?>
                    </span> of <span><?= $total_records ?></span> results
                </div>
                <form class="pagination" method="get">
                    <?php
                        if (!empty($status_filter) && $status_filter !== 'default') echo '<input type="hidden" name="quote-status" value="' . $status_filter . '">';
                    ?>
                    <button type="submit" name="page" value="<?php echo $current_page - 1 ?>" class="previous-page page-btn" <?php if($current_page === 1) echo "disabled"?>>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7071 5.29289C13.0976 5.68342 13.0976 6.31658 12.7071 6.70711L9.41421 10L12.7071 13.2929C13.0976 13.6834 13.0976 14.3166 12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289L11.2929 5.29289C11.6834 4.90237 12.3166 4.90237 12.7071 5.29289Z" fill="#6B7280"/>
                        </svg>
                    </button>
                    <?php
                        if($current_page > 1) {
                            echo "
                                <button type='submit' name='page' value='1' class='page-btn'>
                                    1
                                </button>
                            ";
                        }
                        $has_previous = false;
                        for ($i = $current_page, $firstPages = 0; $i <= $page_count; $i++, $firstPages++) { 
                            if(!$has_previous && $current_page > 2) {
                                $prev = $i - 1;
                                echo "
                                    <button type='submit' name='page' value='{$prev}' class='page-btn'>
                                        {$prev}
                                    </button>
                                ";
                                $has_previous = true;
                            }
                            if($firstPages < 3 || $page_count - $i < 3) {
                                $is_active_page = $current_page === $i ? 'active-page' : '';
                                $disabled = $current_page === $i ? 'disabled' : '';
                                echo "
                                    <button type='submit' name='page' value='{$i}' class='page-btn {$is_active_page}' {$disabled}>
                                        {$i}
                                    </button>
                                ";
                            }
                        }
                    ?>
                    <button type="submit" value="<?php echo $current_page + 1 ?>" name="page" class="next-page page-btn" <?php if($current_page === (int)$page_count) echo "disabled"?>>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.29289 14.7071C6.90237 14.3166 6.90237 13.6834 7.29289 13.2929L10.5858 10L7.29289 6.70711C6.90237 6.31658 6.90237 5.68342 7.29289 5.29289C7.68342 4.90237 8.31658 4.90237 8.70711 5.29289L12.7071 9.29289C13.0976 9.68342 13.0976 10.3166 12.7071 10.7071L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071Z" fill="#6B7280"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const statusSelector = document.querySelector('[name="quote-status"]');
        statusSelector.addEventListener('change', (e) => {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('quote-status', e.target.value);
            window.location.href = currentUrl.href;
        });

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const quoteStatus = urlParams.get('quote-status');
        if (quoteStatus) {
            statusSelector.value = quoteStatus;
        }
    </script>
</body>
</html>