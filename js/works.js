const typeSelector = document.getElementById('type-selector');
const colorSelector = document.getElementById('color-selector');
const galleryImages = document.querySelectorAll('.gallery-item');

function filterGallery() {
    const typeFilter = typeSelector.value;
    const colorFilter = colorSelector.value;

    // console.log(galleryImages);
    galleryImages.forEach((image) => {
        // console.log(image);
        let typeMatch = typeFilter === 'all' || image.dataset.category === typeFilter;
        let colorMatch = colorFilter === 'all' || image.dataset.color === colorFilter;
        // console.log(typeMatch, colorMatch);
        if (typeMatch && colorMatch) {
            // console.log(image.parentElement);
            image.style.display = 'inline';
        } else {
            image.style.display = 'none';
        }
    });
}

// const productGallery = document.querySelector('.product-gallery');

// const checkURLParams = () => {
//     if (window.location.search !== '') {
//         const category = window.location.search.split('=')[1];
//         productGallery.scrollIntoView();
//         typeSelector.value = category;
//         filterGallery();
//     }
// }

// checkURLParams();