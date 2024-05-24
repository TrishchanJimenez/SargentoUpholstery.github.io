<?php
    require '../database_connection.php';
    session_start();
    if(!(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== "admin")) {
        header("Location: ../index.php");
        exit();
    }

    require '../database_connection.php';
    
    // SQL query to get the average rating
    $sqlavg = "SELECT ROUND(AVG(rating),1) AS average_rating FROM reviews";
    $stmt = $conn->prepare($sqlavg);
    $stmt->execute(); 

    // Fetch the result
    $rowavg = $stmt->fetch(PDO::FETCH_ASSOC);
    $average_rating = $rowavg['average_rating'];


    $sqlRepair = "
    SELECT ROUND(AVG(r.rating), 1) AS average_rating1
    FROM orders o
    INNER JOIN reviews r USING(order_id)
    WHERE o.order_type = 'repair'
";

    $stmtRepair = $conn->prepare($sqlRepair);
    $stmtRepair->execute();
    $rowRepair = $stmtRepair->fetch(PDO::FETCH_ASSOC);
    $average_rating_repair = $rowRepair['average_rating1'];

    // SQL query to get the average rating for 'customized' orders
    $sqlCustomized = "
        SELECT ROUND(AVG(r.rating), 1) AS average_rating2
        FROM orders o
        INNER JOIN reviews r ON o.order_id = r.order_id
        WHERE o.order_type = 'mto'
    ";
    $stmtCustomized = $conn->prepare($sqlCustomized);
    $stmtCustomized->execute();
    $rowCustomized = $stmtCustomized->fetch(PDO::FETCH_ASSOC);
    $average_rating_customized = $rowCustomized['average_rating2'];




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php require 'sidebar.php' ?>
        <div class="stat_container">
            <!-- Tab Selector -->

        
            <div class="tab_container">
                <select id="tabSelect" class="dropdown">
                    <option value="Daily">Daily Statistics</option>
                    <option value="Weekly">Weekly Statistics</option>
                    <option value="Monthly">Monthly Statistics</option>
                </select>
                <p id="datetime"></p>
            </div>
        
            <!-- Tab Content for Daily -->
            <div class="contents_container tabcontent" id="Daily">
                <div class="tdmstatistic_container">
                    
                    <?php
                    require '../database_connection.php'; // Adjust path as necessary

                    // SQL query to fetch pending orders for today and yesterday
                    $query = "
                        SELECT
                            (SELECT COUNT(*) FROM orders JOIN order_date USING(order_id) WHERE is_accepted = 'pending' AND DATE(placement_date) = CURDATE()) AS today,
                            (SELECT COUNT(*) FROM orders JOIN order_date USING(order_id) WHERE is_accepted = 'pending' AND DATE(placement_date) = CURDATE() - INTERVAL 1 DAY) AS yesterday;
                    ";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $dailyData = $stmt->fetch(PDO::FETCH_ASSOC);
                    $todayNewOrdersCount = $dailyData['today'];
                    $yesterdayNewOrdersCount = $dailyData['yesterday'];

                    // Calculate trend
                    $trend = $todayNewOrdersCount - $yesterdayNewOrdersCount;
                    $trendPercentage = ($yesterdayNewOrdersCount != 0) ? (($todayNewOrdersCount - $yesterdayNewOrdersCount) / $yesterdayNewOrdersCount) * 100 : 0;

                    ?>
                        <div class="new_orders">
                            <div class="toper">
                                <h1>New Orders</h1>
                                <div class="box">
                                    <p>Today</p>
                                </div>
                            </div>

                            <div class="no">
                                <p><?php echo htmlspecialchars($todayNewOrdersCount); ?></p>
                                <div class="trend <?php echo ($trend >= 0) ? 'positive' : 'negative'; ?>">
                                    <p><?php echo sprintf("%.1f%%", $trendPercentage); ?></p>
                                </div>
                            </div>

                            <div class="compare">
                                <p>Compared to <?php echo htmlspecialchars($yesterdayNewOrdersCount); ?> yesterday</p>
                            </div>

                        </div>

                        <div class="order_status">
                            <div class="toper1">
                                <h1>Order Status</h1>
                                <div class="box">
                                    <p>Today</p>
                                </div>
                            </div>

                            <div class="chart-container">
                                <canvas id="chartDailyStatus"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>  

                        <div class="order_type">
                            <div class="toper1">
                                <h1>Order Type</h1>
                                <div class="box">
                                    <p>Today</p>
                                </div>
                            </div>

                            <div class="chart-container">
                                <canvas id="chartDailyType"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>
                    
                </div>

                <div class="body">
                    <div class="upper">

                        <div class="mostordered_container">
                            <div class="mostordered">
                                <p>Most Ordered</p>
                                <canvas id="myChartD"></canvas>
                            </div>
                        </div>

                        <div class="starrating">

                            <div class="avgrate">
                                <h1>Average Rating<br> Per Service</h1>
                                <div class="starcontents">
                                    <div class="repair">
                                        <div class="type">
                                            <p>Repair</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_repair ?? 'N/A'); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>

                                    </div>

                                    <div class="customized">
                                        <div class="type">
                                            <p>Customized</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_customized); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="avgrate1">
                                <h1>Overall Average Rating</h1>
                                <div class="star1">
                                    <p><?php echo htmlspecialchars($average_rating); ?></p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="26" viewBox="0 0 28 26" fill="none">
                                        <path d="M14 0.790844L17.8006 8.49178L17.8821 8.65678L18.0642 8.68324L26.5627 9.91815L20.4131 15.9125L20.2813 16.0409L20.3124 16.2223L21.7641 24.6864L14.1629 20.6902L14 20.6046L13.8371 20.6902L6.23585 24.6864L7.68757 16.2223L7.71867 16.0409L7.58691 15.9125L1.43734 9.91815L9.93583 8.68324L10.1179 8.65678L10.1994 8.49178L14 0.790844Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                    </svg>
                                </div>
                            </div>

                        </div>

                        <div class="monitoring_container">
                            <div class="monitoring">

                            </div>
                        </div>

                    </div>
                    <div class="linecharts_container">
                        <div class="mid">
                            <div class="orderchart">
                                <div class="description">
                                    <h1>Average Sales</h1>
                                </div>
                                <canvas id="myChart1"></canvas>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- Tab Content for Weekly -->
            <div class="contents_container tabcontent" id="Weekly">
                <div class="tdmstatistic_container">
                   
                    <div>
                        <?php
                        require '../database_connection.php'; // Adjust path as necessary

                        // SQL query to fetch new orders for the current week and the previous week
                        $query = "
                            SELECT 
                                (SELECT COUNT(*) FROM Orders 
                                JOIN Order_date ON Orders.order_id = Order_date.order_id 
                                WHERE YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1)) AS current_week,
                                (SELECT COUNT(*) FROM Orders 
                                JOIN Order_date ON Orders.order_id = Order_date.order_id 
                                WHERE YEARWEEK(placement_date, 1) = YEARWEEK(CURDATE(), 1) - 1) AS last_week;
                        ";

                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $weeklyData = $stmt->fetch(PDO::FETCH_ASSOC);
                        $currentWeekNewOrdersCount = $weeklyData['current_week'];
                        $lastWeekNewOrdersCount = $weeklyData['last_week'];

                        // Calculate trend
                        $trend = $currentWeekNewOrdersCount - $lastWeekNewOrdersCount;
                        $trendPercentage = ($lastWeekNewOrdersCount != 0) ? (($currentWeekNewOrdersCount - $lastWeekNewOrdersCount) / $lastWeekNewOrdersCount) * 100 : 0;
                        ?>
                        <div class="new_orders">
                            <div class="toper">
                                <h1>New Orders</h1>
                                <div class="box">
                                    <p>Weekly</p>
                                </div>
                            </div>
                            <div class="no">
                                <p><?php echo htmlspecialchars($currentWeekNewOrdersCount); ?></p>
                                <div class="trend <?php echo ($trend >= 0) ? 'positive' : 'negative'; ?>">
                                    <p><?php echo sprintf("%.1f%%", $trendPercentage); ?></p>
                                </div>
                            </div>
                            <div class="compare">
                                <p>Compared to <?php echo htmlspecialchars($lastWeekNewOrdersCount); ?> last week</p>
                            </div>
                        </div>

                        <div class="order_status">
                            <div class="toper1">
                                <h1>Order Status</h1>
                                <div class="box">
                                    <p>Weekly</p>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="chartWeeklyStatus"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>

                        <div class="order_type">
                            <div class="toper1">
                                <h1>Order Type</h1>
                                <div class="box">
                                    <p>Weekly</p>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="chartWeeklyType"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <div class="upper">
                        <div class="mostordered_container">
                            <div class="mostordered">
                                <p>Most Ordered</p>
                                <canvas id="myChartW"></canvas>
                            </div>
                        </div>

                        <div class="starrating">
                            <div class="avgrate">
                                <h1>Average Rating<br> Per Service</h1>
                                <div class="starcontents">
                                    <div class="repair">
                                        <div class="type">
                                            <p>Repair</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_repair ?? 'N/A'); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="customized">
                                        <div class="type">
                                            <p>Customized</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_customized); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="avgrate1">
                                <h1>Overall Average Rating</h1>
                                <div class="star1">
                                    <p><?php echo htmlspecialchars($average_rating); ?></p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="26" viewBox="0 0 28 26" fill="none">
                                        <path d="M14 0.790844L17.8006 8.49178L17.8821 8.65678L18.0642 8.68324L26.5627 9.91815L20.4131 15.9125L20.2813 16.0409L20.3124 16.2223L21.7641 24.6864L14.1629 20.6902L14 20.6046L13.8371 20.6902L6.23585 24.6864L7.68757 16.2223L7.71867 16.0409L7.58691 15.9125L1.43734 9.91815L9.93583 8.68324L10.1179 8.65678L10.1994 8.49178L14 0.790844Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="monitoring_container">
                            <div class="monitoring">
                                <!-- Monitoring content -->
                            </div>
                        </div>
                    </div>

                    <div class="linecharts_container">
                        <div class="mid">
                            <div class="orderchart">
                                <div class="description">
                                    <h1>Average Sales</h1>
                                </div>
                                <canvas id="myChart3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tab Content for Monthly -->
            <div class="contents_container tabcontent" id="Monthly">
                <div class="tdmstatistic_container">
                    
                    <div>
                        <?php
                        require '../database_connection.php'; // Adjust path as necessary

                        // SQL query to fetch new orders for the current month and the previous month
                        $query = "
                            SELECT 
                                (SELECT COUNT(*) FROM Orders 
                                JOIN Order_date ON Orders.order_id = Order_date.order_id 
                                WHERE MONTH(placement_date) = MONTH(CURDATE()) AND YEAR(placement_date) = YEAR(CURDATE())) AS current_month,
                                (SELECT COUNT(*) FROM Orders 
                                JOIN Order_date ON Orders.order_id = Order_date.order_id 
                                WHERE MONTH(placement_date) = MONTH(CURDATE()) - 1 AND YEAR(placement_date) = YEAR(CURDATE())) AS last_month;
                        ";

                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $monthlyData = $stmt->fetch(PDO::FETCH_ASSOC);
                        $currentMonthNewOrdersCount = $monthlyData['current_month'];
                        $lastMonthNewOrdersCount = $monthlyData['last_month'];

                        // Calculate trend
                        $trend = $currentMonthNewOrdersCount - $lastMonthNewOrdersCount;
                        $trendPercentage = ($lastMonthNewOrdersCount != 0) ? (($currentMonthNewOrdersCount - $lastMonthNewOrdersCount) / $lastMonthNewOrdersCount) * 100 : 0;

                        ?>
                        <div class="new_orders">
                            <div class="toper">
                                <h1>New Orders</h1>
                                <div class="box">
                                    <p>Monthly</p>
                                </div>
                            </div>

                            <div class="no">
                                <p><?php echo htmlspecialchars($currentMonthNewOrdersCount); ?></p>
                                <div class="trend <?php echo ($trend >= 0) ? 'positive' : 'negative'; ?>">
                                    <p><?php echo sprintf("%.1f%%", $trendPercentage); ?></p>
                                </div>
                            </div>

                            <div class="compare">
                                <p>Compared to <?php echo htmlspecialchars($lastMonthNewOrdersCount); ?> last month</p>
                            </div>

                        </div>

                        <div class="order_status">
                            <div class="toper1">
                                <h1>Order Status</h1>
                                <div class="box">
                                    <p>Monthly</p>
                                </div>
                            </div>

                            <div class="chart-container">
                                <canvas id="chartMonthlyStatus"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>

                        <div class="order_type">
                            <div class="toper1">
                                <h1>Order Type</h1>
                                <div class="box">
                                    <p>Monthly</p>
                                </div>
                            </div>

                            <div class="chart-container">
                                <canvas id="chartMonthlyType"></canvas>
                            </div>
                            <div class="categories">
                                <!-- Categories or additional content -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="body">
                    <div class="upper">
                        <div class="mostordered_container">
                            <div class="mostordered">
                                <p>Most Ordered</p>
                                <canvas id="myChartM"></canvas>
                            </div>
                        </div>

                        <div class="starrating">
                            <div class="avgrate">
                                <h1>Average Rating<br> Per Service</h1>
                                <div class="starcontents">
                                    <div class="repair">
                                        <div class="type">
                                            <p>Repair</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_repair ?? 'N/A'); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="customized">
                                        <div class="type">
                                            <p>Customized</p>
                                        </div>
                                        <div class="star">
                                            <p><?php echo htmlspecialchars($average_rating_customized); ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                                <path d="M10 0.822791L12.6222 6.40154L12.7016 6.5705L12.8861 6.59866L18.7837 7.49847L14.5049 11.8777L14.3814 12.0042L14.4099 12.1788L15.4163 18.3401L10.1691 15.4436L10 15.3502L9.83085 15.4436L4.58371 18.3401L5.59014 12.1788L5.61865 12.0042L5.49506 11.8777L1.21632 7.49847L7.11386 6.59866L7.29842 6.5705L7.37783 6.40154L10 0.822791Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="avgrate1">
                                <h1>Overall Average Rating</h1>
                                <div class="star1">
                                    <p><?php echo htmlspecialchars($average_rating); ?></p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="26" viewBox="0 0 28 26" fill="none">
                                        <path d="M14 0.790844L17.8006 8.49178L17.8821 8.65678L18.0642 8.68324L26.5627 9.91815L20.4131 15.9125L20.2813 16.0409L20.3124 16.2223L21.7641 24.6864L14.1629 20.6902L14 20.6046L13.8371 20.6902L6.23585 24.6864L7.68757 16.2223L7.71867 16.0409L7.58691 15.9125L1.43734 9.91815L9.93583 8.68324L10.1179 8.65678L10.1994 8.49178L14 0.790844Z" fill="#FEB703" stroke="#967C4E" stroke-width="0.7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="monitoring_container">
                            <div class="monitoring">
                                <!-- Monitoring content -->
                            </div>
                        </div>
                    </div>
                    <div class="linecharts_container">
                        <div class="mid">
                            <div class="orderchart">
                                <div class="description">
                                    <h1>Average Sales</h1>
                                </div>
                                <canvas id="myChart5"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

</div>



    <!-- JavaScript -->
    <script src="../js/dashboard.js"></script>
    <script src="../js/Dcharts.js"></script>
    <script src="../js/Wcharts.js"></script>
    <script src="../js/Mcharts.js"></script>
</body>

</html>