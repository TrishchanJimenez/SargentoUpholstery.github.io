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
                    messageElement.innerHTML = message.message; // Change textContent to innerHTML
                    messageElement.className = 'admin-chat__message';
                    messagesContainer.appendChild(messageElement);
                });
            })
            .catch(error => console.error('Error fetching messages:', error));
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