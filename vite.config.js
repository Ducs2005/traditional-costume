import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    define: {
        'process.env': {
            VITE_PUSHER_APP_ID: process.env.PUSHER_APP_ID,
            VITE_PUSHER_APP_KEY: process.env.PUSHER_APP_KEY,
            VITE_PUSHER_APP_CLUSTER: process.env.PUSHER_APP_CLUSTER,
        },
    },
});
