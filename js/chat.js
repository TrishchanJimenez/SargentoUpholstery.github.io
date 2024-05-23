document.addEventListener('DOMContentLoaded', () => {
    const chatButton = document.getElementById('chatButton');
    const chatWindow = document.getElementById('chatWindow');
    const closeChatWindow = document.getElementById('closeChatWindow');
    const chatInput = document.getElementById('chatInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.getElementById('chatMessages');

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
        chatWindow.style.display = chatWindow.style.display == 'flex' ? 'none' : 'flex';
    });

    closeChatWindow.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });
});