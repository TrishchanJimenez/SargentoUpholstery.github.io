const typeSelector = document.getElementById('type-selector');
const colorSelector = document.getElementById('color-selector');

function filterGallery() {
    const typeFilter = typeSelector.value;
    const colorFilter = colorSelector.value;
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach((product) => {
        let typeMatch = typeFilter === 'all' || product.classList.contains(typeFilter);
        let colorMatch = colorFilter === 'all' || product.classList.contains(colorFilter);
        if (typeMatch && colorMatch) {
            product.style.display = 'inline-block';
        } else {
            product.style.display = 'none';
        }
    });
}

const productGallery = document.querySelector('.product-gallery');

const checkURLParams = () => {
    if (window.location.search !== '') {
        const category = window.location.search.split('=')[1];
        productGallery.scrollIntoView();
        typeSelector.value = category;
        filterGallery();
    }
}

checkURLParams();