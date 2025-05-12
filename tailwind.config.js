// tailwind.config.js

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    // ✅ डार्क मोडलाई 'class' बाट सक्षम गरिएको
    darkMode: 'class',

    // 📁 सबै Blade, PHP, JS, CSS र HTML फाइलहरूमा Tailwind क्लासहरूको लागि पथहरू
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './public/build/**/*.html'
    ],

    // 🎨 थीम विस्तार
    theme: {
        extend: {
            // 📚 फन्ट परिवार
            fontFamily: {
                // मूल फिगट्री फन्टको साथ स्यान्स
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // नेपाली फन्ट (Preeti)
                nepali: ['Preeti', 'sans-serif']
            },

            // 🎨 कस्टम रङ्गहरू
            colors: {
                'nepali-red': '#DC143C',   // नेपाली रातो
                'nepali-blue': '#003893',  // नेपाली निलो
                'nepali-green': '#228B22'  // नेपाली हरियो
            },

            // 🌀 ब्लर र छायाहरू
            backdropBlur: {
                'lg': '12px',
                'xl': '20px'
            },
            boxShadow: {
                'glass': '0 8px 32px rgba(31, 38, 135, 0.1)'
            }
        },
    },

    // 🧩 प्लगिनहरू
    plugins: [
        forms,
        typography
    ],
};
