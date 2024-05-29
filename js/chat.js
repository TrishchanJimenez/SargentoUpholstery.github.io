document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chatButton');
    const chatWindow = document.getElementById('chatWindow');
    const closeChatWindow = document.getElementById('closeChatWindow');
    const chatInput = document.getElementById('chatInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.getElementById('chatMessages');
    const newMessageIndicator = document.getElementById('newMessageIndicator');

    let isChatOpen = false;

    // Function to display a message in the chat window
    function displayMessage(message, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', sender);
        messageElement.innerHTML = `
            <span class="timestamp">${new Date().toLocaleTimeString()}</span>
            <p>${message}</p>
        `;
        chatMessages.appendChild(messageElement);
        // Scroll to the bottom of the chat window
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Function to send a message
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message !== '') {
            // Fetch API request to insert the message into the database
            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `message=${encodeURIComponent(message)}`
            })
            .then(response => {
                if (response.ok) {
                    // Message inserted successfully, display it in the chat window
                    displayMessage(message, 'right');
                    // Clear the input field
                    chatInput.value = '';
                } else {
                    console.error('Failed to insert message into the database');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    }

    // Event listeners
    chatButton.addEventListener('click', () => {
        chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
        isChatOpen = chatWindow.style.display === 'flex' ? true : false;
        if (isChatOpen) {
            updateMessageStatus();
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        newMessageIndicator.style.display = 'none';
    });

    closeChatWindow.addEventListener('click', () => {
        chatWindow.style.display = 'none';
        isChatOpen = false;
    });

    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    function checkForNewMessage() {
        fetch('check_new_message.php', {
            method: 'POST',
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                console.error('Failed to retrieve messages from the database');
            }
        })
        .then(data => {
            if (data.has_unread_message && !isChatOpen) {
                newMessageIndicator.style.display = 'flex';
            }
        })
        .catch(error => {
            console.error('Error retrieving messages:', error);
        });
    }

    function updateMessageStatus() {
        if (!isChatOpen) {
            return;
        }
        fetch('update_message_status.php', {
            method: 'POST',
        })
        .then(response => {
            if (!response.ok) {
                console.error('Failed to update message status');
            }
        })
        .catch(error => {
            console.error('Error updating message status:', error);
        });
    }

    function calculateTimeAgo(seconds) {
        if (seconds < 60) {
            return `Just now`;
        }
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) {
            return `${minutes} minutes ago`;
        }
        const hours = Math.floor(minutes / 60);
        if (hours < 24) {
            return `${hours} hours ago`;
        }
        const days = Math.floor(hours / 24);
        return `${days} days ago`;
    }

    // Fetch API request to retrieve messages from the database
    function fetchMessages() {
        fetch('fetch_new_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `lastMessageId=${lastMessageId}`
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                console.error('Failed to retrieve messages from the database');
            }
        })
        .then(({messages, newLastMessageId}) => {
            if (messages.length > 0) {
                messages.forEach(message => {
                    chatMessages.innerHTML += `
                        <div class="message left">
                            <span class="timestamp">${calculateTimeAgo(message.time_diff)}</span>
                            <p>${message.message}</p>
                        </div>
                    `;

                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
                lastMessageId = newLastMessageId;
            }
        })
        .catch(error => {
            console.error('Error retrieving messages:', error);
        });
    }    

    // Check for new messages every second
    setInterval(checkForNewMessage, 1000);
    setInterval(updateMessageStatus, 1000);
    setInterval(fetchMessages, 1000);
});
