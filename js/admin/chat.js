// chat.js

document.addEventListener('DOMContentLoaded', function () {
    // Selectors
    const customerList = document.querySelector('.admin-chat__customer-list-items');
    const chatWindow = document.querySelector('.admin-chat__messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');

    // Function to fetch customers and their latest messages
    function fetchCustomers() {
        fetch('../api/fetch_customers.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Clear previous customer list items
            customerList.innerHTML = '';
            // Populate customer list
            data.forEach(customer => {
                const listItem = document.createElement('li');
                listItem.textContent = customer.customer_name; // Assuming the name field is 'customer_name'
                listItem.dataset.customerId = customer.user_id; // Assuming the ID field is 'user_id'
                listItem.addEventListener('click', () => {
                    fetchMessages(customer.user_id); // Assuming the ID field is 'user_id'
                });
                customerList.appendChild(listItem);
            });
            // Fetch messages for the first customer in the list by default
            if (data.length > 0) {
                fetchMessages(data[0].user_id); // Assuming the ID field is 'user_id'
            }
        })
        .catch(error => console.error('Error fetching customers:', error));
    }

    // Function to fetch messages for a specific customer
    function fetchMessages(customerId) {
        fetch(`../api/fetch_messages.php?customer_id=${customerId}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous messages
            chatWindow.innerHTML = '';
            // Populate chat window with messages
            data.forEach(message => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('admin-chat__message');
                messageElement.textContent = message.message;
                chatWindow.appendChild(messageElement);
            });
            // Scroll chat window to bottom
            chatWindow.scrollTop = chatWindow.scrollHeight;
        })
        .catch(error => console.error('Error fetching messages:', error));
    }

    // Function to send a message
    function sendMessage(message) {
        const customerId = getCurrentCustomerId();
        fetch('../api/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `customer_id=${customerId}&message=${encodeURIComponent(message)}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to send message');
                }
                fetchMessages(customerId); // Refresh messages after sending
            })
            .catch(error => console.error('Error sending message:', error));
    }



    // Fetch customers and their latest messages
    fetchCustomers();

    // Event listener for sending a message
    messageForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const message = messageInput.value.trim();
        if (message !== '') {
            sendMessage(message);
            messageInput.value = '';
        }
    });

    // Function to get the currently selected customer ID
    function getCurrentCustomerId() {
        const selectedCustomer = document.querySelector('.admin-chat__customer-list-items li.active');
        return selectedCustomer ? selectedCustomer.dataset.customerId : null;
    }
});