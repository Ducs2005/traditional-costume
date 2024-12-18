    var chatWindow = document.getElementById('chat-window');
    var closeChatBtn = document.getElementById('closeChat');
    var accountsView = document.getElementById('accountsView');
    var chatInterface = document.getElementById('chatInterface');
    var chatContent = document.getElementById('chatContent');
    var sendMessage =document.getElementById('sendMessageBtn');
    var selectedAccountId = null;
  
    // Close button functionality
    closeChatBtn.addEventListener('click', function() {
        chatWindow.style.display = "none";
        console.log("Chat window closed");
    });

    // Search functionality
    var searchBtn = document.getElementById('searchBtn');
    var searchInput = document.getElementById('searchInput');
    searchBtn.addEventListener('click', function() {
        const searchTerm = searchInput.value.toLowerCase();
        document.querySelectorAll('.account').forEach(function(account) {
            const userName = account.innerText.toLowerCase();
            account.style.display = userName.includes(searchTerm) ? 'block' : 'none';
        });
    });
    

    // Event listener for accounts list with event delegation
    document.getElementById('accountsList').addEventListener('click', function(event) {
        if (event.target.classList.contains('account')) {
            const account = event.target;

            // Check if the account is already clicked
            if (account.classList.contains('unclickable')) {
                return; // Ignore click if account is unclickable
            }

            console.log("Account clicked: ", account.innerText);
            selectedAccountId = account.getAttribute('data-account-id');

            // Make this account unclickable
            account.classList.add('unclickable'); // Visually indicate it's unclickable
            account.style.pointerEvents = 'none'; // Disable pointer events for the account

            // Hide accounts view and show chat interface
            accountsView.style.display = 'none';
            chatInterface.style.display = 'block';

            // Load chat messages for the selected account
            loadChatMessages(selectedAccountId);
        }
    });

    // Back button functionality
    var backToAccountsBtn = document.getElementById('backToAccountsBtn');
    backToAccountsBtn.addEventListener('click', function() {
        var selectedAccountId = null;
        // Hide chat interface and show accounts view
        chatInterface.style.display = 'none';
        accountsView.style.display = 'block';

        // Make all accounts clickable again
        document.querySelectorAll('.account').forEach(function(account) {
            account.classList.remove('unclickable'); // Remove unclickable class
            account.style.pointerEvents = 'auto'; // Restore pointer events
        });
    });

    // Function to fetch and display messages for the selected user
    function loadChatMessages(accountId) {
        const chatContent = document.getElementById('chatContent');
        chatContent.innerHTML = ''; // Clear previous messages
    
        // Use `fetchMessagesUrl` from the Blade template
        fetch(`${fetchMessagesUrl}/${accountId}`)
            .then(response => response.json())
            .then(data => {
                data.messages.forEach(function(message) {
                    // Create the message container
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    
                    // Add 'sent' or 'received' class based on the sender
                    const isReceived = message.sender_id === parseInt(accountId);
                    if (isReceived) {
                        const avatar = document.createElement('img');
                        avatar.src = message.sender_avatar;
                        avatar.alt = 'Avatar';
                        avatar.classList.add('message-avatar');
                        messageElement.appendChild(avatar);
                    }
                    messageElement.classList.add(isReceived ? 'received' : 'sent');
    
                    // Add the avatar for received messages
                    
    
                    // Add the message text
                    const textElement = document.createElement('div');
                    textElement.classList.add('message-text');
                    textElement.innerText = message.message;
                    messageElement.appendChild(textElement);
    
                    chatContent.appendChild(messageElement);
                });
            })
            .catch(error => console.error('Error fetching messages:', error));
    }
    
    
    // This code should only run once
if (!sendMessage.hasAttribute('data-listener-attached')) {
    sendMessage.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default form submission
        const messageInput = document.getElementById('messageInput');
        const messageText = messageInput.value.trim();
    
        if (messageText && selectedAccountId) {
            const messageData = {
                message: messageText,
                receiver_id: selectedAccountId,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
    
            // Disable the send button to prevent multiple clicks
            sendMessage.disabled = true;
            fetch(`${sendMessageUrl}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': messageData._token
                },
                body: JSON.stringify(messageData)
            })
            .then(response => {
                
                // Check if the response is okay (status in the range 200-299)
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })

            .then(data => {
                sendMessage.disabled = false; // Re-enable the send button
                if (data.status_code == 200) {
                    // Clear the input field
                    messageInput.value = '';
    
                    // Append the sent message to the chat interface
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message', 'sent');
                    messageElement.innerText = messageText;
                    chatContent.appendChild(messageElement);
    
                    // Scroll to the bottom of the chat
                    chatContent.scrollTop = chatContent.scrollHeight;
                } else {
                    console.log('Response:', response);
                    console.error('Failed to send message:', data);
                }
            })
            .catch(error => {
                sendMessage.disabled = false; // Re-enable the send button in case of error
                console.error('Error sending message:', error);
            });
        } else {
            console.warn('Message input is empty or no recipient selected.');
        }
    });
    sendMessage.setAttribute('data-listener-attached', 'true');
}
