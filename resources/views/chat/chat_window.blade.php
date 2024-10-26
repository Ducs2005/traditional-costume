<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend/css/chat_window.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Add CSRF token for secure AJAX -->
    <title>Chat Window</title>
</head>
<body>
    <!-- Chat Window -->
    <div id="chat-window" class="chat-window" style="display: none;"> <!-- Start hidden -->
        <div class="chat-header">
            <span>Tin nhắn</span>
            <button id="closeChatBtn" class="close-chat-btn">&times;</button>
        </div>
        <div class="chat-body">
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

            <!-- Chat content (dynamic) -->
            <div id="chatContent" class="chat-content">
                <!-- Placeholder for chat messages -->
            </div>

            <!-- Message input -->
            <div class="message-input">
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button id="sendMessageBtn">Send</button>
            </div>
        </div>
    </div>

    <!-- Chat Toggle Button -->
    <button id="openChatBtn">Tin nhắn</button>

    <script>
        // Fetch the CSRF token from the meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var chatWindow = document.getElementById('chat-window');
        var closeChatBtn = document.getElementById('closeChatBtn');
        var openChatBtn = document.getElementById('openChatBtn');
        var sendMessageBtn = document.getElementById('sendMessageBtn');
        var messageInput = document.getElementById('messageInput');
        var chatContent = document.getElementById('chatContent');
        var selectedAccountId = null; // Track the selected user to chat with

        // Toggle chat window
        openChatBtn.addEventListener('click', function() {
            chatWindow.style.display = "block"; // Show the chat window
        });

        // Close button functionality
        closeChatBtn.addEventListener('click', function() {
            chatWindow.style.display = "none"; // Hide the chat window
        });

        // Listen for clicks on the account list to fetch messages
        document.querySelectorAll('.account').forEach(function(account) {
            account.addEventListener('click', function() {
                selectedAccountId = this.getAttribute('data-account-id');
                fetchMessages(selectedAccountId);
            });
        });

        // Fetch messages for a selected user
        function fetchMessages(accountId) {
            fetch(`/fetch-messages/${accountId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear current chat content
                chatContent.innerHTML = '';

                // Display the messages in the chat window
                data.messages.forEach(function(message) {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.innerText = message.content;
                    chatContent.appendChild(messageElement);
                });
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
        }

        // Send a message
        sendMessageBtn.addEventListener('click', function() {
            const messageContent = messageInput.value;
            if (messageContent && selectedAccountId) {
                sendMessage(selectedAccountId, messageContent);
            }
        });

        // Send a message via AJAX
        function sendMessage(accountId, messageContent) {
            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    recipient_id: accountId,
                    content: messageContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.innerText = messageContent;
                    chatContent.appendChild(messageElement);
                    messageInput.value = ''; // Clear the input
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    </script>
</body>
</html>
