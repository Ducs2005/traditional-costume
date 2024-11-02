//import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


/*

window.Pusher = Pusher;
Pusher.logToConsole = true;

console.log(import.meta.env.VITE_PUSHER_APP_KEY);
const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Uses the cluster from .env
    forceTLS: true,
    encrypted: true,
    authEndpoint: `${import.meta.env.VITE_APP_URL}/pusher/auth`,
    
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});

 
const authId = document.querySelector('meta[name="auth-id"]').getAttribute('content');
const channelName = `chat.${authId}`;
console.log('Subscribing to channel:', channelName); // Log the channel name


const channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        console.log("Received data:", data);
    });
// Listen for messages on the specific channel
echo.channel(channelName)
    .listen('SendMessage', (e) => {
        
    });

*/
async function initializePusher() {
    // Fetch Pusher configuration from your backend
    const response = await fetch(`${import.meta.env.VITE_APP_URL}/api/pusher-config`);

    const config = await response.json();

    // Initialize Pusher with the received configuration
    const pusher = new Pusher(config.key, {
        cluster: config.cluster,
        encrypted: true,
    });
    const authId = document.querySelector('meta[name="auth-id"]').getAttribute('content');

    // Subscribe to a channel
    const channel = pusher.subscribe(`chat.${authId}`);

    // Bind to an event
    channel.bind('SendMessage', function(e) {
        console.log('listen to event');
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', 'received');
        messageElement.innerText = e.message;
        chatContent.appendChild(messageElement);

        // Scroll to the bottom of the chat
        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

// Call the function to initialize Pusher
initializePusher();
