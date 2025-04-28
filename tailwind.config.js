import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',  // CSS पथ अपडेट गरियो
        './public/build/**/*.html'    // Public build फाइलहरू थपियो
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                nepali: ['Preeti', 'sans-serif']  // नेपाली फन्ट थपियो
            },
            backdropBlur: {
                'lg': '12px',
                'xl': '20px'  // नयाँ ब्लर साइज थपियो
            },
            colors: {
                'nepali-red': '#DC143C',
                'nepali-blue': '#003893',
                'nepali-green': '#228B22'  // नयाँ रङ्ग थपियो
            },
            boxShadow: {
                'glass': '0 8px 32px rgba(31, 38, 135, 0.1)'  // Glassmorphism shadow
            }
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/typography')  // टाइपोग्राफी प्लगइन थपियो
    ],
};
