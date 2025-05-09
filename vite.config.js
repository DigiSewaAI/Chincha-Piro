// vite.config.js
import { defineConfig } from 'vite'; // Vite को मुख्य कन्फिगरेसन फंक्शन
import laravel from 'laravel-vite-plugin'; // Laravel Vite Plugin
import path from 'path'; // Path मोड्युल (फाइल पथ ह्यान्डल गर्न प्रयोग हुन्छ)

export default defineConfig({
    // Laravel Vite Plugin सेटिङ्ग्स
    plugins: [
        laravel({
            // कम्पाइल हुने फाइलहरूको सूची
            input: [
                'resources/css/app.css',   // CSS मुख्य फाइल
                'resources/js/app.js',     // JavaScript मुख्य फाइल
            ],
            refresh: true, // स्वतः ताजा गर्ने
        }),
    ],

    // मॉड्युल एलियस कन्फिगरेसन
    resolve: {
        alias: {
            // Laravel Echo को लागि वास्तविक पथ (dist/echo.js सँग)
            // path.resolve प्रयोग गरेर सही फाइल खोज्ने
            'laravel-echo': path.resolve(__dirname, 'node_modules/laravel-echo/dist/echo.js'),
            // Pusher SDK को लागि पनि आवश्यक भएमा थप्न सकिन्छ
            'pusher-js': path.resolve(__dirname, 'node_modules/pusher-js/dist/web/pusher.js'),
            // '@' एलियस सामान्यतया JS फाइलहरूको लागि प्रयोग हुन्छ
            '@': path.resolve(__dirname, './resources/js')
        }
    }
});
