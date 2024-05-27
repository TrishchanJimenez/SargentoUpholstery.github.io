<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

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
                <input type="submit" class="form__submit" value="Submit Review">
            </div>
        </form>
    </div>
</div>
<div>
    <button type="button" class="button button--review" onclick="openReviewModal()">Review</button>
</div>