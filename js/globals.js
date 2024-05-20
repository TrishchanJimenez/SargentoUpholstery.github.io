//Navigation Menu for small screen
const openBtn = document.getElementById('open-btn');
const closeBtn = document.getElementById('close-btn');
const menu = document.getElementById('offcanvas-menu')

openBtn.addEventListener('click', (e) => {
    menu.classList.add('active');
});

closeBtn.addEventListener('click', (e) => {
    menu.classList.remove('active');
});

const accountBtn = document.getElementById('account-btn');
const accountMenu = document.querySelector('.account-menu');
accountBtn.addEventListener('click', () => {
    if (accountBtn.parentElement.classList.contains('nav-icons')) {
        accountMenu.classList.toggle('show');
    }
})

// ---------- Alert ---------- //

document.addEventListener('DOMContentLoaded', function () {
    const closeButtons = document.querySelectorAll('.alert__close-button');

    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const alert = this.parentElement;
            alert.style.display = 'none';
        });
    });
});

// ---------- Notifs ---------- //

document.addEventListener("DOMContentLoaded", function () {
    const bellIcon = document.querySelector(".notif__bell");
    const notifContainer = document.getElementById("notifContainer");

    bellIcon.addEventListener("click", function () {
        notifContainer.style.display = notifContainer.style.display === "block" ? "none" : "block";
    });
});



document.addEventListener("DOMContentLoaded", function () {
    const markReadButtons = document.querySelectorAll(".mark-read-btn");

    markReadButtons.forEach(button => {
        button.addEventListener("click", function () {
            const notifId = this.parentElement.getAttribute("data-notif-id");
            markNotifAsRead(notifId);
        });
    });

    function markNotifAsRead(notifId) {
        fetch("/api/mark_notif_as_read.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `notif_id=${notifId}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Failed to mark notif as read");
                }
                return response.text();
            })
            .then(data => {
                console.log(data); // Output success message
            })
            .catch(error => {
                console.error(error); // Log error message
            });
    }
});