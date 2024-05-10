<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="display_reviews.css"> <!-- Link to display_reviews.css -->
</head>
<body>
    <div class="container">
        <div class="reviews-container">
            <?php
            session_start(); // Start the session

            // Include database connection
            include_once "db_connection.php";

            $_SESSION['is_admin'] = true;

            // Function to get initials from username
            function getInitials($username) {
                $parts = explode(' ', $username);
                $initials = '';
                foreach ($parts as $part) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
                return $initials;
            }

            // Retrieve existing reviews from the database
            $sql = "SELECT * FROM reviews ORDER BY review_id DESC";
            $result = $conn->query($sql);

            // Display reviews
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['datetime']);
                    $rdate = DateTime::createFromFormat('Y-m-d H:i:s', $row['reply_datetime']);
                    $formattedDate = $date->format('F j, Y');
                    $formattedReplyDate = $rdate->format('F j, Y');

                    echo '<div class="review" id="review-' . $row['review_id'] . '">';
                    echo '<div class="user-info">';
                    echo '<div class="avatar">' . getInitials($row['username']) . '</div>';
                    echo '<div class="username">' . $row['username'] . '</div>';
                    echo '</div>';
                    echo '<div class="rating">';
                    echo '<div class="Star-Container">';
                    echo '<span class="star-rating"></span>';
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<span class="star">' . ($i <= $row['rating'] ? '&#9733;' : '&#9734;') . '</span>';
                    }
                    echo '</div>';
                    echo '<div class="date">' . $formattedDate . '</div>';
                    echo '</div>';
                    echo '<div class="Details">' . 
                            'Service: ' . $row['type'] . '<span> | </span>' . 
                            'Furniture: ' . $row['category'] . '<span> | </span>' .  
                            'Color: ' . $row['color'] . '<span> | </span>' . 
                         '</div>';
                    echo '<div class="review-text">' . $row['comment'] . '</div>';

                    // Display additional images
                    if (!empty($row['images'])) {
                        $images = explode(',', $row['images']);
                        echo '<div class="review-images">';
                        foreach ($images as $image) {
                            echo '<img src="' . $image . '" alt="Review Image">';
                        }
                        echo '</div>';
                    }

                    // Display reply form for admin if there's no reply yet and rating is 1
                    if (empty($row['reply']) && $row['rating'] == 1 && $_SESSION['is_admin']) {
                        echo '<form id="reply-form' . $row['review_id'] . '" class="reply-form" action="submit_reply.php" method="post">';
                        echo '<input type="hidden" name="review_id" value="' . $row['review_id'] . '">';
                        echo '<textarea name="reply" class="Text" placeholder="Reply to this review..."></textarea>';
                        echo '<div class="Button_Container">';
                        echo '<input type="submit" value="Save" class="button_submit">';
                        echo '<input type="button" value="Cancel" class="button_submit" onclick="cancelReply(' . $row['review_id'] . ')">';
                        echo '</div>';
                        echo '</form>';
                    } else if (!empty($row['reply'])) { // Display admin reply if it exists
                        echo '<div class="admin-reply">';
                        echo '<div class="admin-reply-container">';
                        echo '<div class="admin-reply-thing"></div>';
                        echo '<div class="info">';
                        echo '<div class="topper">' . 'Response: Sargento Upholstery - ' . $formattedReplyDate . '</div>';
                        echo '<div class="reply">' . $row['reply'] . '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }

                    echo '</div>'; // Close review container
                }
            } else {
                echo "<p>No reviews yet.</p>";
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
        
    </div>

    <script src="display_reviews.js"></script> <!-- Link to display_reviews.js -->

</body>
</html>
