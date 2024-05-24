<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/user_orders.css">
    <link rel="stylesheet" href="/css/review_submission.css">
    <script src="/js/user_orders.js"></script>
    <title>My Orders</title>
</head>

<body>
    <?php include_once("../header.php"); ?>
    <div class="review-background">
        <div class="review-content">
            <div class="review-header">
                <h1>Rate Service</h1>
                <span class="close-modal" onclick="closeReviewModal()">&times;</span>
            </div>
            <form id="reviewForm" action="" method="post" enctype="multipart/form-data">
                <div class="starcontainer">
                    <p>Rate Quality:</p>
                    <div class="rating" id="ratingStars">
                        <input type="hidden" id="rating" name="rating" value="">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div><br><br>
                </div>
                <label for="review">Review:</label><br>
                <textarea id="review" name="comment" rows="4" cols="50" placeholder="Enter review here..." required></textarea><br><br>
                <label for="images">Additional Images:</label>
                <div class="file-drop" id="imagesDrop" onclick="document.getElementById('images').click();">
                    <span>Click Here to Add Images</span>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                </div>
                <div id="imagesPreview"></div><br><br>
                <input type="submit" value="Submit Review">
            </form>
        </div>
    </div>
    <script src="/js/review_submission.js"></script>
</body>
</html>