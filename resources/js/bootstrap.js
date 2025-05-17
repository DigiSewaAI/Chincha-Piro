import Echo from "laravel-echo";

// Pusher JS लाइब्रेरी आवश्यक छ यदि तपाईं Pusher वा Laravel WebSockets प्रयोग गर्दै हुनुहुन्छ
window.Pusher = require('pusher-js');

// Echo कन्फिगरेसन
window.Echo = new Echo({
    broadcaster: 'pusher', // Pusher वा Laravel WebSockets प्रयोग गरिरहेको
    key: process.env.MIX_PUSHER_APP_KEY || 'laravel-websockets', // .env मा MIX_PUSHER_APP_KEY सेट गर्नुहोस्
    wsHost: process.env.MIX_PUSHER_HOST || window.location.hostname, // स्थानीय विकासका लागि 127.0.0.1 वा domain
    wsPort: process.env.MIX_PUSHER_PORT || 6001, // WebSockets पोर्ट
    forceTLS: false, // स्थानीयमा HTTPS बिना काम गर्न
    disableStats: true, // सांख्यिकी बन्द गर्न
    enabledTransports: ['ws'], // केवल WebSocket प्रयोग गर्न (wss वा ws)
});
