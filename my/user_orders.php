<button?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
    include_once("../database_connection.php");
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sargento Upholstery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/user_orders.css">
    <link rel="stylesheet" href="/css/review_submission.css">
</head>

<body>
    <?php include_once("../header.php") ?>


    <div class="header-tabs">
        <button class="header-tab active" onclick="openTabb(event, 'tab0')">Orders</button>
        <button class="header-tab" onclick="openTabb(event, 'tab00')">Quotes</button>
    </div>

    <div id="tab0" class="tab-content" style="display:block;">
        <!-- Tab buttons -->
    <div class="tab-container">
        <ion-icon class="left-btn" name="chevron-back-outline"></ion-icon>
        <ion-icon class="right-btn" name="chevron-forward-outline"></ion-icon>
        <div id="tab-buttons" class="scrollable-tab">
            <button class="tab-button" onclick="openTab(event, 'tab1')">All Orders</button>
            <button class="tab-button" onclick="openTab(event, 'tab2')">Pending</button>
            <button class="tab-button" onclick="openTab(event, 'tab3')">Accepted</button>
            <button class="tab-button" onclick="openTab(event, 'tab4')">Ready For Pickup</button>
            <button class="tab-button" onclick="openTab(event, 'tab5')">In Production</button>
            <button class="tab-button" onclick="openTab(event, 'tab6')">To Be Delivered</button>
            <button class="tab-button" onclick="openTab(event, 'tab7')">On Delivery</button>
            <button class="tab-button" onclick="openTab(event, 'tab8')">Received</button>
            <button class="tab-button" onclick="openTab(event, 'tab9')">Cancelled</button>
            <button class="tab-button" onclick="openTab(event, 'tab10')">Rejected</button>
        </div>
    </div>
    <!-- Tab content -->
    <!--all orders -->
    <div id="tab-content">
        <!-- all orders -->
        <div id="tab1" class="tab active">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id,
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status, 
                            o.is_cancelled 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                            <table class="tabLabels">
                                <tr class="status-header">
                                    <th>Item description</th>
                                    <th>Quoted Price</th>
                                    <th>Delivery address</th>
                                    <th>Order type</th>
                                    <th>Status</th> 
                                    <th>Details</th>
                                </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $prod_status = str_replace("_", "-", $row['order_status']);
                        $prod_status_text = ucwords(str_replace("-", " ", $prod_status));
                        $display_status = '';

                        if($row['is_cancelled'] === 1) {
                            $display_status = 'Cancelled';
                        } else if ($row['is_accepted'] !== 'accepted') {
                            $display_status = ucfirst($row['is_accepted']);
                        } else {
                            $display_status = ucwords(str_replace('_', ' ', $row['order_status']));
                        }

                        echo '
                                <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                    <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                    <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                    <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                    <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                    <td><div class="tab-table"><p>' . htmlspecialchars($display_status) . '</p></div></td>
                                    <td class="myTable">
                                        <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                                <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            ';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>

        </div>
        <!-- pending -->
        <div id="tab2" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            o.is_accepted = 'pending'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Status</th> 
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($display_status) . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>

        <!-- Accepted -->
        <div id="tab3" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                    SELECT 
                    o.order_id, 
                    o.furniture_type, 
                    o.quoted_price, 
                    a.address, 
                    o.order_type, 
                    o.is_accepted, 
                    o.order_status,
                    p.payment_status
                    FROM 
                        orders o
                    INNER JOIN
                        addresses a ON o.del_address_id = a.address_id
                    INNER JOIN
                        payment p ON o.order_id = p.order_id -- Join with the payment table using the common column
                    WHERE 
                        o.user_id = :user_id 
                        AND o.is_accepted = 'accepted'
                        AND p.payment_status = 'unpaid'
                        AND order_status = 'pending_downpayment';
                        ;
                
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Status</th> 
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($display_status) . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!--  ready for pickup -->
        <div id="tab4" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            o.order_status = 'ready_for_pickup'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!-- in production-->
        <div id="tab5" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            order_status = 'in_production'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!-- to be delivered -->
        <div id="tab6" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            order_status ='pending_fullpayment'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!--on delivery -->
        <div id="tab7" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            order_status ='out_for_delivery'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!-- Received -->
        <div id="tab8" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            order_status ='received'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!-- cancelled -->
        <div id="tab9" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            o.is_cancelled = 1
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
        <!-- rejected -->
        <div id="tab10" class="tab">
            <?php
            try {
                // Query to select data from the database
                $sql = "
                        SELECT 
                            o.order_id, 
                            o.furniture_type, 
                            o.quoted_price, 
                            a.address, 
                            o.order_type, 
                            o.is_accepted, 
                            o.order_status 
                        FROM 
                            orders o
                                INNER JOIN
                            addresses a
                                ON o.del_address_id = a.address_id
                        WHERE 
                            o.user_id = :user_id 
                                AND 
                            o.is_accepted = 'rejected'
                    ";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Output the table header outside the loop
                    echo '
                        <table class="tabLabels">
                            <tr class="status-header">
                                <th>Item description</th>
                                <th>Quoted Price</th>
                                <th>Delivery address</th>
                                <th>Order type</th>
                                <th>Details</th>
                            </tr>';

                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $display_status = $row["order_status"] == 'received' ? 'Received' : ($row["is_accepted"] == 'accepted' ? 'Accepted' : 'Pending');
                        // Output the table rows inside the loop
                        echo '
                            <tr class="order-container" data-id="' . htmlspecialchars($row["order_id"]) . '">
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["furniture_type"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . (is_null($row["quoted_price"]) ? "N/A" : "₱" . htmlspecialchars($row["quoted_price"])) . '</p></div></td>
                                <td><div class="tab-table"><p>' . htmlspecialchars($row["address"]) . '</p></div></td>
                                <td><div class="tab-table"><p>' . ($row["order_type"] === "mto" ? "MTO" : "Repair") . '</p></div></td>
                                <td class="myTable">
                                    <a href="user_order_details.php?order-id=' . htmlspecialchars($row["order_id"]) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6B7280">
                                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>';
                        // Add spacing between order-container elements
                        echo '<tr class="order-container-space"></tr>';
                    }

                    // Close the table outside the loop
                    echo '</table>';
                } else {
                    echo "0 results";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            ?>
        </div>
    </div>

    </div>

    <div id="" class="tab"></div>
    <script src="/js/my/orders_and_quotes.js"></script>
    <script src="/js/user_orders.js"></script>
    <script src="/js/globals.js"></script>
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>