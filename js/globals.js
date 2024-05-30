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
        
        // Close notifications if open
        if (notifContainer.style.display === "block") {
            notifContainer.style.display = "none";
        }
    }
})

// ---------- Notifs ---------- //

document.addEventListener("DOMContentLoaded", function () {
    const bellIcon = document.querySelector(".notif__bell");
    const notifContainer = document.getElementById("notifContainer");
    const markReadButtons = document.querySelectorAll(".mark-read-btn");

    bellIcon.addEventListener("click", function () {
        notifContainer.style.display = notifContainer.style.display === "block" ? "none" : "block";
        // Close account menu if open
        if (accountMenu.classList.contains('show')) {
            accountMenu.classList.remove('show');
        }
    });

    markReadButtons.forEach(button => {
        button.addEventListener("click", function () {
            const notifElement = this.parentElement;
            const notifId = notifElement.getAttribute("data-notif-id");
            markNotifAsRead(notifId, notifElement);
        });
    });

    function markNotifAsRead(notifId, notifElement) {
        fetch("/api/mark_notif_as_read.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `notif_id=${notifId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to mark notification as read");
            }
            return response.text();
        })
        .then(data => {
            console.log(data); // Output success message
            // Update the notification appearance
            notifElement.classList.remove("unread");
            const markReadImgUnread = notifElement.querySelector('.mark-read-img--unread');
            const markReadImgRead = notifElement.querySelector('.mark-read-img--read');
            markReadImgUnread.style.display = "none";
            markReadImgRead.style.display = "inline-block";
        })
        .catch(error => {
            console.error("Error:", error); // Log error message
        });
    }
});