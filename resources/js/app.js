import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Toastify Import (यदि CDN मार्फत प्रयोग गरिएको छैन भने)
// import Toastify from 'toastify-js';
// import "toastify-js/src/toastify.css"

// Laravel Echo Configuration
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    client: Pusher,
});

// Order Button Listener
document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const dishId = this.dataset.dishId;

        // Dish-Specific Order Channel
        window.Echo.private(`orders.${dishId}`)
            .listen('.OrderPlaced', (e) => {
                Toastify({
                    text: `नयाँ अर्डर: ${e.order.dish.name} (${e.order.quantity} पोर्शन)`,
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#DC2626',
                    className: 'nepali-font',
                }).showToast();
            });
    });
});

// Global Order Notification (सबै अर्डरहरूका लागि)
window.Echo.channel('orders')
    .listen('.NewOrderEvent', (e) => {
        console.log('नयाँ अर्डर भेटियो:', e.order);

        Toastify({
            text: `नयाँ अर्डर: #${e.order.id} - ${e.order.customer_name}`,
            duration: 4000,
            gravity: 'bottom',
            position: 'center',
            backgroundColor: '#003893',
            className: 'nepali-font',
            callback: () => {
                Livewire.emit('newOrder'); // Livewire Admin Panel लाई Refresh गर्नुहोस्
            }
        }).showToast();
    });
