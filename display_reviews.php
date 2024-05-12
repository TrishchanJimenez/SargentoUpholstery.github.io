<?php
    session_start(); // Start the session
    require "database_connection.php";

    // Function to get initials from username
    function getInitials($username) {
        $parts = explode(' ', $username);
        $initials = '';
        for ($i=0; $i < 2; $i++) { 
            $initials .= strtoupper(substr($parts[$i], 0, 1));
        }
        return $initials;
    }
    
    // Retrieve existing reviews from the database
    $sql = "
        SELECT
            U.name,
            IF(O.order_type = 'mto', 'MTO', 'Repair') AS order_type,
            O.furniture_type,
            R.review_id,
            R.comment,
            R.date,
            R.rating,
            R.reply,
            R.reply_date,
            GROUP_CONCAT(IFNULL(RI.path, '') SEPARATOR ',') AS review_images
        FROM reviews R 
        JOIN orders O ON R.order_id = O.order_id
        JOIN users U ON U.user_id = O.user_id
        LEFT JOIN review_images RI ON RI.review_id = R.review_id
        GROUP BY R.review_id
        ORDER BY R.review_id DESC;
    ";
    $result = $conn->query($sql);
    $reviews = $result->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/display_reviews.css">
</head>
<body>
    <div class='container'>
        <div class="reviews-container">
            <?php
            foreach($reviews AS $review) {
                $date = date('M d, Y', strtotime($review['date']));
                if(!is_null($review['reply_date'])) {
                    $reply_date = date('M d, Y', strtotime($review['reply_date'])); 
                }
                
                $initials = getInitials($review['name']);

                $stars = "";
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= "<span class='star'>";
                    $stars .= $i <= (int)$review['rating'] ? '&#9733;' : '&#9734;';
                    $stars .= "</span>";
                }
                $review_images = "<div class='review-images'>"; 
                if (!empty($row['images'])) {
                    $images = explode(',', $row['images']);
                    foreach ($images as $image) {
                        $review_images .= "<img src={$images} alt='Review Image'>";
                    }
                }
                $review_images .= "</div>";
                echo "
                    <div class='review' id='review'>
                        <div class='user-info'>
                            <span class='avatar'>{$initials}</span>
                            <span class='username'>{$review['name']}</span>
                        </div>
                        <div class='rating'>
                            <div class='star-container'>
                                {$stars}
                            </div>
                            <div class='date'>{$date}</div>
                        </div>
                        <div class='details'>
                            Service: {$review['order_type']}<span> | </span>
                            Furniture: {$review['furniture_type']}
                        </div>
                        <div class='review-text'>{$review['comment']}</div>
                ";
                if (empty($review['reply']) && $_SESSION['access_type'] === 'admin') {
                    echo "
                        <input type='button' value='Reply' class='button-submit reply-button'>
                        <form id='reply-form' class='reply-form' method='post'>
                            <input type='hidden' name='review_id' value='{$review['review_id']}'>
                            <textarea name='reply' id='' placeholder='Reply to this review...' class='text'></textarea>
                            <div class='button-container'>
                                <input type='submit' value='Save' class='button-submit submit-reply'>
                                <input type='button' value='Cancel' class='button-submit cancel-reply'>
                            </div>
                        </form>
                    ";
                }
                if (!empty($review['reply'])) { // Display admin reply if it exists
                    echo "
                        <div class='admin-reply'>
                            <div class='admin-reply-container'>
                                <div class='admin-reply-thing'> </div>
                                <div class='info'>
                                    <div class='topper'>Response: Sargento Upholstery - {$reply_date}</div>
                                    <div class='reply'>{$review['reply']}</div>
                                </div>
                            </div>
                        </div>
                    ";
                }
                echo "</div>"; // Close review container
            }
            // Display reviews
            if($result->rowCount() === 0) {
                echo "<p>No reviews yet.</p>";
            }
            ?>
        </div>
    </div>
    <script src="/js/display_reviews.js"></script> <!-- Link to display_reviews.js -->
</body>
</html>