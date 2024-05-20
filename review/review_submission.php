<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Review</title>
    <link rel="stylesheet" href="Review_Submission.css">
    <style>
        /* CSS styles for drop zones */
        .file-drop {
            border: 2px dashed #ccc;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s ease-in-out;
        }

        .file-drop.highlight {
            border-color: #f39c12;
        }

        .file-drop input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rate Product</h1>
        <form id="reviewForm" action="submit_review.php" method="post" enctype="multipart/form-data">
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
            <label for="comment">Review:</label><br>
            <textarea id="comment" name="review" rows="4" cols="50" required></textarea><br><br>
            <label for="images">Additional Images:</label>
            <div class="file-drop" id="imagesDrop" onclick="document.getElementById('images').click();">
                <span>Drag and drop additional images here or click to select (multiple)</span>
                <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(event, 'imagesPreview')">
            </div>
            <div id="imagesPreview"></div><br><br>
            <input type="submit" value="Submit Review">
        </form>
    </div>
    <script src="Review_Submission.js"></script>
</body>
</html>