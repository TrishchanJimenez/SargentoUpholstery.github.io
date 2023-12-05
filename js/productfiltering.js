function filterGallery() {
    var typeFilter = document.getElementById('type-selector').value;
    var colorFilter = document.getElementById('color-selector').value;

    var galleryItems = document.getElementsByClassName('gallery-item');

    for (var i = 0; i < galleryItems.length; i++) {
        var item = galleryItems[i];

        // Check if the item matches the selected filters
        var typeMatch = typeFilter === 'all' || item.classList.contains(typeFilter);
        var colorMatch = colorFilter === 'all' || item.classList.contains(colorFilter);

        // Show or hide the item based on the filter matches
        if (typeMatch && colorMatch) {
            item.style.display = 'inline-block';
        } else {
            item.style.display = 'none';
        }
    }
}