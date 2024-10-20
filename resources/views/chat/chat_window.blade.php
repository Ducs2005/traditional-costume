<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('frontend/css/chat_window.css')}}">
    <title>Chat Window</title>
</head>
<body>
    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window">
        <div class="chat-header">
            <span>Tin nhắn</span>
            <button id="closeChatBtn" class="close-chat-btn">&times;</button>
        </div>
        <div class="chat-body">
            <!-- Accounts List -->
            <div id="accountsList" class="accounts-list">
                <!-- Placeholder for the list of accounts -->
                
                <ul>
                    <li class="account" data-account-id="1">Account 1</li>
                    <li class="account" data-account-id="2">Account 2</li>
                    <li class="account" data-account-id="3">Account 3</li>
                </ul>
            </div>

            <!-- Chat content (dynamic) -->
            <div id="chatContent" class="chat-content">
                <!-- Placeholder for chat messages -->
               
            </div>
        </div>
    </div>

    <!-- External JS -->
<script src="{{asset('frontend/js/chat.js')}}"></script>
</body>
</html>
