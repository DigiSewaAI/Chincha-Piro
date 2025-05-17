import { defineConfig } from 'vite'; // Vite को मुख्य कन्फिगरेसन फंक्शन
import laravel from 'laravel-vite-plugin'; // Laravel Vite Plugin
import path from 'path'; // Path मोड्युल (फाइल पथ ह्यान्डल गर्न प्रयोग हुन्छ)

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss', // ✅ SCSS प्रयोग गर्नुभएकोले यो फाइल हुनुपर्छ
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // JS imports को लागि एलियस
            'laravel-echo': path.resolve(__dirname, 'node_modules/laravel-echo/dist/echo.js'),
            'pusher-js': path.resolve(__dirname, 'node_modules/pusher-js/dist/web/pusher.js'),
        },
    },
});
