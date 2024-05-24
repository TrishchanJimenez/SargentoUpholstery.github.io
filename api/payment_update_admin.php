<?php
    require 'database_connection.php'; 
    if(isset($_POST['payment_phase'])) {
        $payment_phase = $_POST['payment_phase'];
        $is_verified = $_POST['is_verified'] === "true" ? true : false;     
    }
?>