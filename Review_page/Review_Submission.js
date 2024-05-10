document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            ratingInput.value = value;

            highlightStars(value);
        });

        star.addEventListener('mouseover', () => {
            const value = star.getAttribute('data-value');
            highlightStars(value);
        });

        star.addEventListener('mouseout', () => {
            const value = ratingInput.value;
            highlightStars(value);
        });
    });

    function highlightStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= value) {
                star.style.color = '#f39c12';
            } else {
                star.style.color = '#ddd';
            }
        });
    }

    // Initialize star colors based on default rating value (if any)
    highlightStars(ratingInput.value);
});

// Function to preview avatar image
function previewImage(event, previewId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const avatarDrop = document.getElementById('avatarDrop');
    const imagesDrop = document.getElementById('imagesDrop');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        avatarDrop.addEventListener(eventName, preventDefaults, false);
        imagesDrop.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        avatarDrop.addEventListener(eventName, highlightDropZone, false);
        imagesDrop.addEventListener(eventName, highlightDropZone, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        avatarDrop.addEventListener(eventName, unhighlightDropZone, false);
        imagesDrop.addEventListener(eventName, unhighlightDropZone, false);
    });

    avatarDrop.addEventListener('drop', handleFileDrop.bind(null, 'avatar'), false);
    imagesDrop.addEventListener('drop', handleFileDrop.bind(null, 'images'), false);
});

function preventDefaults(event) {
    event.preventDefault();
    event.stopPropagation();
}

function highlightDropZone(event) {
    event.currentTarget.classList.add('highlight');
}

function unhighlightDropZone(event) {
    event.currentTarget.classList.remove('highlight');
}

function handleFileDrop(inputName, event) {
    const fileList = event.dataTransfer.files;
    const fileInput = document.getElementById(inputName);

    if (fileList.length > 0) {
        fileInput.files = fileList;
        updateDropZoneText(inputName + 'Drop', `${fileList.length} file(s) selected`);
    }
}

function updateDropZoneText(dropZoneId, text) {
    const dropZone = document.getElementById(dropZoneId);
    dropZone.querySelector('span').textContent = text;
}