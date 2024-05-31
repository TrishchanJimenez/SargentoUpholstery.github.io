const tabSelector = document.querySelector('.tab-selector');
const allTabs = document.querySelectorAll('.tab');
const allTabButtons = document.querySelectorAll('.tab-button');
const currentTab = document.querySelector('.active-tab'); 

document.addEventListener('DOMContentLoaded', () => {
    const lastTab = sessionStorage.getItem('lastTab');
    if (lastTab) {
        const tabToActivate = document.querySelector(`[data-tab="${lastTab}"]`);
        if (tabToActivate) {
            tabToActivate.click();
        } 
    } else {
        const homeTab = document.querySelector('[data-tab="home"]');
        if (homeTab) {
            homeTab.click();
        }
    }
});

tabSelector.addEventListener('click', (e) => {
    if (!e.target.classList.contains('tab-button')) return;
    const tabName = e.target.dataset.tab;
    sessionStorage.setItem('lastTab', tabName);
    allTabs.forEach(tab => {
        if (tab.dataset.page === tabName) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    allTabButtons.forEach(tabButton => {
        if (tabButton.dataset.tab === tabName) {
            tabButton.classList.add('active');
        } else {
            tabButton.classList.remove('active');
        }
    });
});

const modal = document.querySelector('.modal-background');
const modalTitle = modal.querySelector('.edit-title');
const longTextArea = modal.querySelector('textarea[name="content_text"]');

const editShortTextForm = modal.querySelector('form[name="edit-short-text"]');
const editLongTextForm = modal.querySelector('form[name="edit-long-text"]');
const editFaqForm = modal.querySelector('form[name="edit-faq"]');
const editGalleryForm = modal.querySelector('form[name="add-gallery-image"]');
const editImageForm = modal.querySelector('form[name="edit-website-image"]');

const imageUploadGallery = editGalleryForm.querySelector('#fileInput');
const imagePreviewGallery = editGalleryForm.querySelector('#image-preview');

const imageUploadWebsite = editImageForm.querySelector('#fileInput');
const imagePreviewWebsite = editImageForm.querySelector('#image-preview');

const textPlaceholder = editShortTextForm.querySelector('input[type="text"]');

const questionInput = modal.querySelector('input[name="faq-question"]');    
const answerInput = modal.querySelector('textarea[name="faq-answer"]');    

const submitFaqBtn = editFaqForm.querySelector(' .btn-save');
const deleteFaqBtn = editFaqForm.querySelector(' .btn-delete');
const backTopBtn = document.querySelector('.back-to-top');

let longTextEdit;
let shortTextEdit;
let faqItem;
let content_id;
let faq_id;
let clickedFaqOption;
let modalIsOpen;
let imageToEdit;

currentTab.addEventListener('click', (e) => {
    // IF GALLERY ITEM
    const galleryItem = e.target.closest('.gallery-item');
    if(galleryItem !== null) {
        displayForm('add-image');
        // IF ADD IMAGE
        if(galleryItem.classList.contains('add-image')) {
            modalTitle.innerText = "Add Gallery Image";
            imageUploadGallery.required = true;
            openModal();
            addGalleryImageForm.addEventListener('submit', (e) => {
                e.preventDefault(); 
                const insertData = new FormData(addGalleryImageForm);
                insertData.append('action', 'insert');
                fetch('/api/Gallery.php', {
                    method: 'POST',
                    body: insertData
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // console.log(response.text());
                    return response.json();
                }).then(data => {
                    addGalleryImageBtn.insertAdjacentHTML('afterend', `
                        <div class='gallery-item'>
                            <img src='${data.img_path}' class='gallery-image' data-color='${data.color}' data-category='${data.category}' data-id='${data.works_id}'>
                            <div class='action-buttons'>
                                <button class='edit-button'>Edit</button>
                                <button class='delete-button'>Delete</button>
                            </div>
                        </div>
                    `);
                    console.log(data);
                    closeModal();
                });
            })
        }

        // IF EDIT IMAGE
        if(e.target.classList.contains('edit-button')) {
            const id = galleryItem.querySelector('img').dataset.id;
            modalTitle.innerText = "Edit Gallery Image";
            const categoryText = addGalleryImageForm.querySelector('[name="category"]');
            const colorText = addGalleryImageForm.querySelector('[name="color"]');
            fetch(`/api/Gallery.php?id=${id}`, {
                method: 'GET'
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(data => {
                console.log(data);
                categoryText.value = data.category;
                colorText.value = data.color;
                imagePreviewGallery.src = data.img_path;
                imageUploadGallery.required = false;
            });
            openModal();
            addGalleryImageForm.addEventListener('submit', (e) => {
                e.preventDefault(); 
                const insertData = new FormData(addGalleryImageForm);
                insertData.append('works_id', id);
                insertData.append('action', 'update');
                fetch('/api/Gallery.php', {
                    method: 'POST',
                    body: insertData
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // console.log(response.text());
                    return response.json();
                }).then(data => {
                    console.log(data);
                    const imgToUpdate = document.querySelector(`.gallery-image[data-id='${id}']`);
                    imgToUpdate.dataset.category = data.category;
                    imgToUpdate.dataset.color = data.color;
                    imgToUpdate.src = data.img_path;
                    closeModal();
                });
            })
        }

        // IF DELETE IMAGE
        if(e.target.classList.contains('delete-button')) {
            const id = galleryItem.querySelector('img').dataset.id;
            // console.log(id);
            confirm('Are you sure you want to delete this image?');
            const deleteData = new FormData();
            deleteData.append('id', id);
            deleteData.append('action', 'delete');
            console.log(deleteData);
            if (confirm) {
                fetch('/api/Gallery.php', {
                    method: 'POST',
                    body: deleteData
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    // console.log(response.text());
                    return response.json();
                }).then(data => {
                    galleryItem.remove();
                    // console.log(data);
                });
            }
        }
     
        imageUploadGallery.addEventListener('change', (e) => {
            console.log('upload');
            const file = imageUploadGallery.files;
            if (file) {
                console.log('test');
                const fileReader = new FileReader();
                fileReader.onload = event => {
                    imagePreviewGallery.setAttribute('src', event.target.result);
                }
                fileReader.readAsDataURL(file[0]);
            }
        });
    }

    // IF SHORT TEXT
    shortTextEdit = e.target.closest('.short-text')
    if(shortTextEdit !== null) {
        displayForm('edit-short-text');
        content_id = shortTextEdit.dataset.id;
        console.log(content_id);
        modalTitle.innerText = "Edit Info";
        editShortTextForm.reset();
        textPlaceholder.value = shortTextEdit.innerText;
        openModal();
        textPlaceholder.focus();
        editShortTextForm.removeEventListener('submit', handleShortTextFormSubmit);
        editShortTextForm.addEventListener('submit', handleShortTextFormSubmit);
    }

    // IF LONG TEXT
    longTextEdit = e.target.closest('.long-text')
    if(longTextEdit !== null) {
        displayForm('edit-long-text');
        content_id = longTextEdit.dataset.id;
        console.log(content_id);
        modalTitle.innerText = "Edit Info";
        longTextArea.value = longTextEdit.innerText;
        openModal();
        autoResizeTextArea();
        longTextArea.focus();
        editLongTextForm.removeEventListener('submit', handleLongTextFormSubmit);
        editLongTextForm.addEventListener('submit', handleLongTextFormSubmit);
    }

    // IF FAQ
    faqItem = e.target.closest('.faq__item'); 
    if(faqItem !== null) {
        displayForm('edit-faq');
        faq_id = faqItem.dataset.faqId;
        modalTitle.innerText = "Edit FAQ";
        questionInput.value = faqItem.querySelector('.faq__question').innerText;
        answerInput.value = faqItem.querySelector('.faq__answer').innerText;
        openModal();
        autoResizeAnswerInput();

        editFaqForm.removeEventListener('submit', handleFaqFormSubmit);
        editFaqForm.addEventListener('submit', handleFaqFormSubmit);
    }

    imageToEdit = e.target.closest('.image-edit');
    if(imageToEdit !== null) {
        displayForm('edit-website-image');
        modalTitle.innerText = "Edit Image";
        content_id = imageToEdit.dataset.id;
        imagePreviewWebsite.src = imageToEdit.querySelector('img').src; 
        openModal();

        imageUploadWebsite.addEventListener('change', (e) => {
            console.log('upload');
            const file = imageUploadWebsite.files;
            if (file) {
                console.log('test');
                const fileReader = new FileReader();
                fileReader.onload = event => {
                    imagePreviewWebsite.setAttribute('src', event.target.result);
                }
                fileReader.readAsDataURL(file[0]);
            }
        });

        editImageForm.removeEventListener('submit', handleImageFormSubmit);
        editImageForm.addEventListener('submit', handleImageFormSubmit);
    }
});

function openModal() {
    modal.style.display = 'block';
    imagePreviewGallery.src = '';
    backTopBtn.style.display = 'none';
    modalIsOpen = true;
}

function closeModal() {
    modal.style.display = "none";
    modalIsOpen = false;
    checkTopBtnPosition();
}

function cancelModal() {
    confirm('Are you sure you want to cancel?');
    closeModal();
}

const addGalleryImageForm = modal.querySelector('form[name="add-gallery-image"]');
const addGalleryImageBtn = document.querySelector('.add-image');

const allInputForms = modal.querySelectorAll('form');

function displayForm(type) {
    allInputForms.forEach(form => {
        if (form.dataset.for === type) {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
        form.reset();
    });
}

const typeSelector = document.getElementById('type-selector');
const colorSelector = document.getElementById('color-selector');
const galleryImages = document.querySelectorAll('.gallery-image');

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
            image.parentElement.style.display = 'inline';
        } else {
            image.parentElement.style.display = 'none';
        }
    });
}

longTextArea.addEventListener('input', autoResizeTextArea, false);
answerInput.addEventListener('input', autoResizeAnswerInput, false);

function autoResizeTextArea() {
    console.log(longTextArea.scrollHeight);
    const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

    longTextArea.style.height = 'auto'; // reset the height
    longTextArea.style.height = (longTextArea.scrollHeight + longTextArea.offsetHeight - longTextArea.clientHeight) + 'px';

    // restore the scroll position of the page
    document.documentElement.scrollTop = scrollPosition;
    document.body.scrollTop = scrollPosition;
}

function autoResizeAnswerInput() {
    console.log(answerInput.scrollHeight);
    const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

    answerInput.style.height = 'auto'; // reset the height
    answerInput.style.height = (answerInput.scrollHeight + answerInput.offsetHeight - answerInput.clientHeight) + 'px';

    // restore the scroll position of the page
    document.documentElement.scrollTop = scrollPosition;
    document.body.scrollTop = scrollPosition;
}

// Define the function outside the event listener
function handleShortTextFormSubmit(e) {
    e.preventDefault(); 
    const insertData = new FormData(editShortTextForm);
    insertData.append('content_id', content_id);
    console.log(insertData);
    fetch('/api/Content.php', {
        method: 'POST',
        body: insertData
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        // console.log(response.text());
        return response.json();
    }).then(data => {
        console.log(data);
    });
    shortTextEdit.innerText = textPlaceholder.value;
    closeModal();
}

function handleLongTextFormSubmit(e) {
    e.preventDefault(); 
    const insertData = new FormData(editLongTextForm);
    insertData.append('content_id', content_id);
    console.log(insertData);
    fetch('/api/Content.php', {
        method: 'POST',
        body: insertData
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        // console.log(response.text());
        return response.json();
    }).then(data => {
        console.log(data);
    });
    longTextEdit.innerHTML = longTextArea.value;
    closeModal();
}

submitFaqBtn.addEventListener('click', function() {
    clickedButton = 'save';
});

deleteFaqBtn.addEventListener('click', function() {
    clickedButton = 'delete';
});

function handleFaqFormSubmit(e) {
    e.preventDefault(); 
    const insertData = new FormData(editFaqForm);
    insertData.append('faq_id', faq_id);
    if (clickedButton === 'delete') {
        insertData.append('action', 'delete');
    } else {
        insertData.append('action', 'update');
    }
    console.log(insertData);
    fetch('/api/Faq.php', {
        method: 'POST',
        body: insertData
    }).then(response => {
        // console.log(response.text());
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }).then(data => {
        console.log(data);
    });
    if (clickedButton === 'save') {
        faqItem.querySelector('.faq__question').innerText = questionInput.value;
        faqItem.querySelector('.faq__answer').innerText = answerInput.value;
    } else {
        faqItem.remove();
    }
    // imageToEdit.querySelector('img').src = imagePreviewWebsite.src;
    closeModal();
}

function handleImageFormSubmit(e) {
    e.preventDefault(); 
    const imageData = new FormData();    
    imageData.append('content_id', content_id);
    console.log(imageData);
    fetch('/api/Content.php', {
        method: 'POST',
        body: imageData
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response.text());
        return response.json();
    }).then(data => {
        console.log(data);
    });
}

backTopBtn.addEventListener('click', function() {
    window.scrollTo(0, 0);
});

window.addEventListener('scroll', checkTopBtnPosition);
function checkTopBtnPosition() {
    if (window.pageYOffset > window.innerHeight && !modalIsOpen) {
        backTopBtn.style.display = 'block';
    } else {
        backTopBtn.style.display = 'none';
    }
}