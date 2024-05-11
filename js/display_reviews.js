const reviewContainer = document.querySelector('.reviews-container');

reviewContainer.addEventListener('click', (e) => {
    const target = e.target;
    if(target.classList.contains('reply-button')) {
        const replyForm = target.parentNode.querySelector('.reply-form');
        const submitBtn = replyForm.querySelector(' .submit-reply');
        const cancelBtn = replyForm.querySelector(' .cancel-reply');
        target.style.display = 'none';
        replyForm.style.display = 'block';

        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const replyData = new FormData(replyForm);
            const review = target.closest('.review');
            fetch('/updater/submit_reply.php', {
                method: "POST",
                body: replyData
            })
            .then(res => {
                console.log(res);
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            }).then(data => {
                const date = new Date(data.reply_date);
                const formattedDate = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                });
                review.innerHTML += `
                <div class='admin-reply'>
                    <div class='admin-reply-container'>
                        <div class='admin-reply-thing'> </div>
                        <div class='info'>
                            <div class='topper'>Response: Sargento Upholstery - ${formattedDate}</div>
                            <div class='reply'>${data.reply}</div>
                        </div>
                    </div>
                </div> 
                `;
            }).catch(error => {console.error('Error: ', error)})
            replyForm.style.display = 'none';
        }) 

        cancelBtn.addEventListener('click', (e) => {
            target.style.display = 'block';
            replyForm.style.display = 'none';
        })
    }
})