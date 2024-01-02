function filterGallery() {
    const typeFilter = document.getElementById('type-selector').value;
    const colorFilter = document.getElementById('color-selector').value;
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