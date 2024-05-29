// Function to open a specific tab
function openTab(event, tabName) {
    // Hide all tabs
    var tabs = document.getElementsByClassName("tab");
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("active");
    }
    // Deactivate all tab buttons
    var tabButtons = document.getElementsByClassName("tab-button");
    for (var i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }
    // Show the selected tab
    document.getElementById(tabName).classList.add("active");
    // Activate the selected tab button
    event.currentTarget.classList.add("active");
}

function openTabb(evt, tabId) {
    var i, tabcontent, tablinks;

    // Hide all tab contents
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove the class "active" from all tab buttons
    tablinks = document.getElementsByClassName("header-tab");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Show the current tab content
    document.getElementById(tabId).style.display = "block";
    
    // Add the class "active" to the clicked tab button
    evt.currentTarget.classList.add("active");
}




// Open the first tab by default

//proof-payment-form
// document.getElementById('attachmentForm').addEventListener('submit', function (event) {
//     event.preventDefault(); // Prevent default form submission
//     var formData = new FormData(); // Create a FormData object
//     var fileInput = document.getElementById('fileInput'); // Get the file input element
//     var files = fileInput.files; // Get the selected files
//     // Check if files are selected
//     if (files.length > 0) {
//         // Append the files to FormData
//         for (var i = 0; i < files.length; i++) {
//             var file = files[i];
//             formData.append('attachments[]', file, file.name);
//         }
//         // You can send the FormData to the server using AJAX or submit the form
//         // For demonstration, let's just log the FormData
//         console.log(formData);
//     } else {
//         alert('Please select a file.');
//     }
// });

// Javascript for tab navigation horizontal scroll buttons

// const btnLeft = document.querySelector('.left-btn');
// const btnRight = document.querySelector('.right-btn');

// const iconVisibility = () => {
// 	let scrollLeftValue = Math.ceil(tabMenu.scrollLeft);
// 	console.log('1.', scrollLeftValue);

// 	let scrollableWidth = tabMenu.scrollWidth - tabMenu.clientWidth;
// 	console.log('2.', scrollableWidth);

// 	btnLeft.style.display = scrollLeftValue > 0 ? 'block' : 'none';
// 	btnRight.style.display = scrollableWidth > scrollLeftValue ? 'block' : 'none';
// };

// btnRight.addEventListener('click', () => {
// 	tabMenu.scrollLeft += 150;
// 	//iconVisibility();
// 	setTimeout(() => iconVisibility(), 50);
// });
// btnLeft.addEventListener('click', () => {
// 	tabMenu.scrollLeft -= 150;
// 	//iconVisibility();
// 	setTimeout(() => iconVisibility(), 50);
// });

// window.onload = function () {
// 	btnRight.style.display =
// 		tabMenu.scrollWidth > tabMenu.clientWidth
// 			|| tabMenu.scrollWidth >= window.innerWidth
// 			? 'block' : 'none';
// 	btnLeft.style.display = tabMenu.scrollWidth >= window.innerWidth ? '' : 'none';
// };

// window.onresize = function () {
// 	btnRight.style.display =
// 		tabMenu.scrollWidth > tabMenu.clientWidth
// 			|| tabMenu.scrollWidth >= window.innerWidth
// 			? 'block' : 'none';
// 	btnLeft.style.display = tabMenu.scrollWidth >= window.innerWidth ? '' : 'none';

// 	let scrollLeftValue = Math.round(tabMenu.scrollLeft);
// 	btnLeft.style.display = scrollLeftValue > 0 ? 'block' : 'none';
// };

// Javascript to make the tab navigation draggable
window.onload = function () {
    const btnLeft = document.querySelector(".left-btn");
    const btnRight = document.querySelector(".right-btn");
    const tabMenu = document.querySelector("#tab-buttons");

    const iconVisibility = () => {
        let scrollableWidth = tabMenu.scrollWidth - tabMenu.clientWidth;
        console.log("2.", scrollableWidth);

        let scrollLeftValue = Math.ceil(tabMenu.scrollLeft);
        if(scrollLeftValue <= 5) {
            scrollLeftValue = 0;
        } else if(scrollLeftValue >= scrollableWidth - 5) {
            scrollLeftValue = scrollableWidth;
        }
        console.log("1.", scrollLeftValue);

        btnLeft.style.display = scrollLeftValue > 0 ? "block" : "none";
        btnRight.style.display = scrollableWidth > scrollLeftValue ? "block" : "none";
    };

    btnRight.addEventListener("click", () => {
    tabMenu.scrollLeft += 150;
    //iconVisibility();
    setTimeout(() => iconVisibility(), 50);
    });
    btnLeft.addEventListener("click", () => {
    tabMenu.scrollLeft -= 150;
    //iconVisibility();
    setTimeout(() => iconVisibility(), 50);
    });

    window.onload = function () {
    btnRight.style.display =
        tabMenu.scrollWidth > tabMenu.clientWidth ||
        tabMenu.scrollWidth >= window.innerWidth
        ? "block"
        : "none";
    btnLeft.style.display =
        tabMenu.scrollWidth >= window.innerWidth ? "" : "none";
    };

    window.onresize = function () {
    btnRight.style.display =
        tabMenu.scrollWidth > tabMenu.clientWidth ||
        tabMenu.scrollWidth >= window.innerWidth
        ? "block"
        : "none";
    btnLeft.style.display =
        tabMenu.scrollWidth >= window.innerWidth ? "" : "none";

    let scrollLeftValue = Math.round(tabMenu.scrollLeft);
    btnLeft.style.display = scrollLeftValue > 0 ? "block" : "none";
    };

    // Javascript to make the tab navigation draggable
    let activeDrag = false;

    tabMenu.addEventListener("mousemove", (drag) => {
    if (!activeDrag) return;
    tabMenu.scrollLeft -= drag.movementX;
    iconVisibility();

    tabMenu.classList.add("dragging");
    });

    document.addEventListener("mouseup", () => {
    activeDrag = false;

    tabMenu.classList.remove("dragging");
    });

    tabMenu.addEventListener("mousedown", () => {
    activeDrag = true;
    });
};