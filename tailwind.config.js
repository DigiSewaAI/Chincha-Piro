import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './public/build/**/*.html'
    ],

    theme: {
        extend: {
            fontFamily: {
                // मूल फिगट्री फन्टको साथ स्यान्स
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // नेपाली फन्ट (Preeti) को लागि थपिएको
                nepali: ['Preeti', 'sans-serif']
            },
            colors: {
                // नेपाली रङ्गहरू
                'nepali-red': '#DC143C',
                'nepali-blue': '#003893',
                'nepali-green': '#228B22'
            },
            backdropBlur: {
                'lg': '12px',
                'xl': '20px'
            },
            boxShadow: {
                'glass': '0 8px 32px rgba(31, 38, 135, 0.1)'
            }
        },
    },

    plugins: [
        forms,
        typography
    ],
};
