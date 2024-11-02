<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="auth-id" content="{{ auth()->id() }}">
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

    @vite(['resources/js/app.js'])
    <script>
        const fetchMessagesUrl = "{{ url('/fetch-messages') }}";
        const sendMessageUrl = "{{ url('/send-message') }}";
        
    </script>
    <script src="{{asset('frontend/js/chat.js')}}"></script>
  

</body>
</html>
