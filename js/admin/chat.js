document.addEventListener('DOMContentLoaded', () => {
    const customerList = document.querySelector('.admin-chat__customer-list-items');
    const messageForm = document.querySelector('#message-form');
    const messageInput = document.querySelector('#message-input');
    const messagesContainer = document.querySelector('.admin-chat__messages');
    let currentCustomerId = null;

    // Function to fetch customers and their latest messages
    function fetchCustomers() {
        fetch('../api/fetch_customers.php')
            .then(response => response.json())
            .then(data => {
                // Clear previous customer list items
                customerList.innerHTML = '';
                // Populate customer list
                data.forEach(customer => {
                    const listItem = document.createElement('li');
                    listItem.textContent = customer.customer_name;
                    listItem.dataset.customerId = customer.user_id;
                    listItem.className = 'admin-chat__customer-list-item';
                    listItem.addEventListener('click', () => {
                        fetchMessages(customer.user_id);
                    });
                    customerList.appendChild(listItem);
                });
                // Fetch messages for the first customer in the list by default
                if (data.length > 0) {
                    fetchMessages(data[0].user_id);
                }
            })
            .catch(error => console.error('Error fetching customers:', error));
    }

    // Function to fetch messages for a specific customer
    function fetchMessages(customerId) {
        currentCustomerId = customerId;
        fetch(`../api/fetch_messages.php?customer_id=${customerId}`)
            .then(response => response.json())
            .then(data => {
                // Clear previous messages
                messagesContainer.innerHTML = '';
                // Populate messages
                data.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.textContent = message.message;
                    messageElement.className = message.sender_id === currentCustomerId ? 
                        'admin-chat__message customer' : 
                        'admin-chat__message admin'; // Apply different classes based on sender_id
                    const timestampElement = document.createElement('span');
                    timestampElement.textContent = formatTimestamp(message.timestamp);
                    timestampElement.className = 'admin-chat__message-timestamp';
                    messagesContainer.appendChild(messageElement);
                    messageElement.appendChild(timestampElement);
                });
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    // Function to format timestamp into human-readable format
    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const currentDate = new Date();
        const diff = currentDate - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        if (days > 0) {
            return days === 1 ? 'Yesterday' : `${days} days ago`;
        } else if (hours > 0) {
            return hours === 1 ? 'An hour ago' : `${hours} hours ago`;
        } else if (minutes > 0) {
            return minutes === 1 ? 'A minute ago' : `${minutes} minutes ago`;
        } else {
            return 'Just now';
        }
    }

    // Event listener for sending a message
    messageForm.addEventListener('submit', event => {
        event.preventDefault();
        const message = messageInput.value.trim();
        if (message && currentCustomerId) {
            fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}&customer_id=${currentCustomerId}`,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to send message');
                }
                // Clear the input field
                messageInput.value = '';
                // Refresh the messages
                fetchMessages(currentCustomerId);
            })
            .catch(error => console.error('Error sending message:', error));
        }
    });

    // Initial fetch of customers
    fetchCustomers();
});
