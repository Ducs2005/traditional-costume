<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/chat_window.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token for future AJAX if needed -->
    <title>Chat Window</title>
</head>
<body>
    <!-- Chat Window -->
    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <span>Tin nhắn</span>
            <button id="closeChat" class="close-chat-btn">&times;</button>
        </div>
        <div class="chat-body">
            <!-- Search and Account List View -->
            <div id="accountsView">
                <!-- Search Field -->
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search users...">
                    <button id="searchBtn">Search</button>
                </div>

                <!-- Accounts List -->
                <div id="accountsList" class="accounts-list">
                    <ul>
                        @forelse($chattedUsers as $user)
                            <li class="account" data-account-id="{{ $user->id }}">{{ $user->name }}</li>
                        @empty
                            <li>No chats yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Chat Interface View -->
            <div id="chatInterface" class="chat-interface" style="display: none;">
                <!-- Back button to go to accounts list -->
                <button id="backToAccountsBtn">Back</button>

                <!-- Chat Content (Dynamic) -->
                <div id="chatContent" class="chat-content">
                    <!-- Placeholder for chat messages -->
                </div>

                <!-- Message input for sending messages (for display purposes only) -->
                <div class="message-input">
                    <input type="text" id="messageInput" placeholder="Type a message...">
                    <button id="sendMessageBtn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var chatWindow = document.getElementById('chat-window');
        var closeChatBtn = document.getElementById('closeChat');
        var accountsView = document.getElementById('accountsView');
        var chatInterface = document.getElementById('chatInterface');
        var backToAccountsBtn = document.getElementById('backToAccountsBtn');
        var chatContent = document.getElementById('chatContent');
        var selectedAccountId = null;

        // Close button functionality
        closeChatBtn.addEventListener('click', function() {
            chatWindow.style.display = "none";
            console.log("click");
        });

        // Search functionality (display only, no backend integration)
        var searchBtn = document.getElementById('searchBtn');
        var searchInput = document.getElementById('searchInput');
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchInput.value.toLowerCase();
            document.querySelectorAll('.account').forEach(function(account) {
                const userName = account.innerText.toLowerCase();
                account.style.display = userName.includes(searchTerm) ? 'block' : 'none';
            });
        });


        //Load message
        var chatContent = document.getElementById('chatContent');
        var selectedAccountId = null; // Track the selected user's ID

        // Event listener for clicking on an account
        document.querySelectorAll('.account').forEach(function(account) {
            account.addEventListener('click', function() {
                selectedAccountId = this.getAttribute('data-account-id');
                loadChatMessages(selectedAccountId);
            });
        });

        // Function to fetch and display messages for the selected user
        function loadChatMessages(accountId) {
            chatContent.innerHTML = ''; // Clear previous messages

            fetch(`/fetch-messages/${accountId}`)
                .then(response => response.json())
                .then(data => {
                    data.messages.forEach(function(message) {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message');

                        // Add 'sent' or 'received' class based on the sender
                        if (message.sender_id === parseInt(accountId)) {
                            messageElement.classList.add('received');
                        } else {
                            messageElement.classList.add('sent');
                        }

                        messageElement.innerText = message.content;
                        chatContent.appendChild(messageElement);
                    });
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

    </script>
</body>
</html>
