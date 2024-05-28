<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect or handle unauthorized access
        header("Location: login.php");
        exit;
    }
?>

<div class="modal__open">
    <button type="button" class="button button--review" onclick="openReviewModal()">Review</button>
</div>
<div class="modal modal--review">
    <div class="modal__content">
        <div class="modal__header">
            <h1 class="modal__title">Rate Service</h1>
            <span class="modal__close" onclick="closeReviewModal()">&times;</span>
        </div>
        <form id="reviewForm" class="form form--review" method="post" enctype="multipart/form-data">
            <input type="hidden" name="order_id" value="<?= $order_id; ?>">
            <div class="form__group form__group--rating">
                <p class="form__label">Rate Quality:</p>
                <div class="rating" id="ratingStars">
                    <input type="hidden" id="rating" name="rating" value="">
                    <span class="rating__star" data-value="1">&#9733;</span>
                    <span class="rating__star" data-value="2">&#9733;</span>
                    <span class="rating__star" data-value="3">&#9733;</span>
                    <span class="rating__star" data-value="4">&#9733;</span>
                    <span class="rating__star" data-value="5">&#9733;</span>
                </div>
            </div>
            <div class="form__group">
                <label class="form__label" for="review">Review:</label>
                <textarea id="review" class="form__textarea" name="comment" rows="4" cols="50" placeholder="Enter review here..." required></textarea>
            </div>
            <div class="form__group">
                <label class="form__label" for="images">Additional Images:</label>
                <div class="form__file-drop" id="imagesDrop" onclick="document.getElementById('images').click();">
                    <span class="form__file-drop-text">Click Here to Add Images</span>
                    <input type="file" id="images" class="form__file-input" name="images[]" multiple accept="image/*" style="display:none;" onchange="previewImages(event)">
                </div>
                <div id="imagesPreview" class="form__images-preview"></div>
            </div>
            <div class="form__group">
                <input type="submit" class="form__submit" name="submit--review" value="Submit Review">
            </div>
        </form>
    </div>
</div>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../notif.php');

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit--review"])) {
        // Sanitize and validate input
        $order_id = $_POST["order_id"];
        $rating = $_POST["rating"];
        $comment = htmlspecialchars($_POST["comment"]);

        // Insert review data into the reviews table
        try {
            $query_review = "INSERT INTO reviews (order_id, rating, comment) VALUES (:order_id, :rating, :comment)";
            $stmt_review = $conn->prepare($query_review);
            $stmt_review->bindParam(':order_id', $order_id);
            $stmt_review->bindParam(':rating', $rating);
            $stmt_review->bindParam(':comment', $comment);
            $stmt_review->execute();

            // Get the ID of the inserted review
            $review_id = $conn->lastInsertId();

            // Check if images were uploaded
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                // Loop through each uploaded image
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    // Check for errors and move the uploaded file
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_name = $_FILES['images']['name'][$key];
                        $file_tmp = $_FILES['images']['tmp_name'][$key];
                        $file_type = $_FILES['images']['type'][$key];

                        // Check if file is an image
                        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
                        if (in_array($file_type, $allowed_types)) {
                            // Generate unique file name and move the file
                            $target_path = '/uploadedImages/reviewImages/' . basename($file_name);
                            move_uploaded_file($file_tmp, $target_path);

                            // Insert image path into review_images table
                            $query_image = "INSERT INTO review_images (review_id, path) VALUES (:review_id, :path)";
                            $stmt_image = $conn->prepare($query_image);
                            $stmt_image->bindParam(':review_id', $review_id);
                            $stmt_image->bindParam(':path', $target_path);
                            $stmt_image->execute();
                        } else {
                            // Handle invalid file type
                            echo "Error: Invalid file type for image: $file_name <br>";
                        }
                    } else {
                        // Handle file upload errors
                        echo "Error uploading image: " . $_FILES['images']['name'][$key] . "<br>";
                    }
                }
            }
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
?>
