// resources/js/app.js

// Import Laravel Echo & Pusher SDK
import Echo from 'laravel-echo';  // Vite सँगै काम गर्ने सही पथ
import Pusher from 'pusher-js';  // Pusher SDK (dist/web/pusher.js मा resolve हुनेछ)

// Import Toast Notification Library
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";

// Nepali Font Support थप्नुहोस्
document.documentElement.classList.add('nepali-font');

// Laravel Echo + Pusher Configuration
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'ap2',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth'
});

// DOM लोड भएपछि Event Listeners थप्नुहोस्
document.addEventListener('DOMContentLoaded', () => {
    // Order Button लाई सब्सक्राइब गर्नुहोस्
    document.querySelectorAll('.order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const dishId = this.dataset.dishId;

            // Dish-specific Order Channel
            window.Echo.private(`orders.${dishId}`)
                .listen('.OrderPlaced', (e) => {
                    Toastify({
                        text: `नयाँ अर्डर: ${e.order.dish.name} (${e.order.quantity} पोर्शन)`,
                        duration: 5000,
                        gravity: 'top',
                        position: 'right',
                        className: 'nepali-font',
                        backgroundColor: '#10B981'
                    }).showToast();
                });
        });
    });

    // Global Order Channel
    window.Echo.channel('orders')
        .listen('.NewOrderEvent', (e) => {
            Toastify({
                text: `नयाँ अर्डर: #${e.order.id} - ${e.order.customer_name}`,
                duration: 4000,
                gravity: 'bottom',
                position: 'center',
                className: 'nepali-font',
                backgroundColor: '#3B82F6',
                callback: () => {
                    if (window.Livewire) {
                        Livewire.emit('newOrder');
                    }
                }
            }).showToast();
        });
});
