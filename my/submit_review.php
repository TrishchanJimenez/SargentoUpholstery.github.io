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

<div class="modal" id="reviewModal">
    <div class="modal__content">
        <span class="modal__close" id="closeReview">&times;</span>
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
<script>
    // const reviewBtn = document.querySelector('.button--review');
    const reviewForm = document.getElementById('reviewForm');
    const ratingStars = document.getElementById('ratingStars');
    const stars = document.querySelectorAll('.rating__star');
    const ratingInput = document.getElementById('rating');
    const imagePreview = document.getElementById('imagesPreview');
    const reviewImageInput = document.getElementById('images');
    let currentStarValue = 0;

    // Open review modal
    // reviewBtn.addEventListener('click', () => {
    //     openReviewModal();
    //     resetStars();
    // });

    // Star rating hover effect
    ratingStars.addEventListener('mouseover', (event) => {
        console.log(event.target);
        if (event.target.classList.contains('rating__star')) {
            const tempStarValue = event.target.getAttribute('data-value');
            highlightStars(tempStarValue);
        }
    });

    // Reset stars on mouse out
    ratingStars.addEventListener('mouseout', () => {
        highlightStars(currentStarValue);
    });

    // Set rating on click
    ratingStars.addEventListener('click', (event) => {
        if (event.target.classList.contains('rating__star')) {
            currentStarValue = event.target.getAttribute('data-value');
            ratingInput.value = currentStarValue;
            highlightStars(currentStarValue);
        }
    });

    // Highlight stars based on rating
    function highlightStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            star.style.color = starValue <= value ? '#f39c12' : '#ddd';
        });
    }

    // Reset stars to initial state
    function resetStars() {
        currentStarValue = 0;
        ratingInput.value = 0;
        highlightStars(0);
    }

    // Preview selected images
    function previewImages(event) {
        imagePreview.innerHTML = '';
        const files = event.target.files;
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const image = document.createElement('img');
                image.src = e.target.result;
                image.classList.add('form__image-preview');
                imagePreview.appendChild(image);
            };
            reader.readAsDataURL(file);
        });
    };

    // Open the review modal
    window.openReviewModal = function() {
        document.querySelector('.modal--review').style.display = 'flex';
    };

    // Close the review modal
    window.closeReviewModal = function() {
        document.querySelector('.modal--review').style.display = 'none';
    };

    // Simulate click to upload image
    window.clickUploadImage = function() {
        reviewImageInput.click();
    };
</script>

<style>
    /* Modal Styles */
    .modal--review {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal__content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 50%;
        max-width: 600px;
    }

    .modal__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal__title {
        margin: 0;
    }

    .modal__open {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .button--review {
        background-color: #f39c12;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 60%;
        height: auto;
        padding: 2.5vmin;
        margin: 1.25vmin;
    }

    .modal__close {
        cursor: pointer;
    }

    /* Form Styles */
    .form--review {
        margin-top: 20px;
    }

    .form__group {
        margin-bottom: 20px;
    }

    .form__label {
        font-weight: bold;
        display: block;
    }

    .rating__star {
        font-size: 2.5rem;
        height: auto;
        cursor: pointer;
        color: #ddd;
    }

    .form__textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
    }

    .form__file-drop {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }

    .form__images-preview {
        margin-top: 10px;
    }

    .form__image-preview {
        max-width: 100px;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .form__submit {
        padding: 10px 20px;
        background-color: #f39c12;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .form__submit:hover {
        background-color: #e68a06;
    }
</style>

<?php
    // Include database connection
    require_once('../database_connection.php');
    include_once('../notif.php');
    require_once('../alert.php');

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
                            $target_path = '../uploadedImages/reviewImages/' . basename($file_name);
                            move_uploaded_file($file_tmp, $target_path);
                            $dbpath = 'uploadedImages/reviewImages/' . basename($file_name);

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

            sendAlert("success", "Review submitted successfully!");
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
?>

