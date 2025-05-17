import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './public/build/**/*.html',
    ],
    theme: {
        screens: {
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1536px',
        },
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            nepali: ['Preeti', 'Noto Sans Devanagari', 'sans-serif'],
            mono: [...defaultTheme.fontFamily.mono],
        },
        colors: {
            inherit: 'inherit',
            current: 'currentColor',
            transparent: 'transparent',
            black: 'rgb(0 0 0 / <alpha-value>)',
            white: 'rgb(255 255 255 / <alpha-value>)',
            'nepal-red': '#DC143C',
            'nepal-blue': '#003893',
            'nepal-green': '#228B22',
            slate: defaultTheme.colors.slate,
            gray: defaultTheme.colors.gray,
            zinc: defaultTheme.colors.zinc,
            neutral: defaultTheme.colors.neutral,
            stone: defaultTheme.colors.stone,
            red: defaultTheme.colors.red,
            orange: defaultTheme.colors.orange,
            amber: defaultTheme.colors.amber,
            yellow: defaultTheme.colors.yellow,
            lime: defaultTheme.colors.lime,
            green: defaultTheme.colors.green,
            emerald: defaultTheme.colors.emerald,
            teal: defaultTheme.colors.teal,
            cyan: defaultTheme.colors.cyan,
            sky: defaultTheme.colors.sky,
            blue: defaultTheme.colors.blue,
            indigo: defaultTheme.colors.indigo,
            violet: defaultTheme.colors.violet,
            purple: defaultTheme.colors.purple,
            fuchsia: defaultTheme.colors.fuchsia,
            pink: defaultTheme.colors.pink,
            rose: defaultTheme.colors.rose,
        },
        extend: {
            colors: {
                primary: {
                    50: '#fff1f1',
                    100: '#ffe4e4',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                    950: '#450a0a',
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
            },
            backdropBlur: {
                lg: '12px',
                xl: '20px',
            },
            boxShadow: {
                glass: '0 8px 32px rgba(31, 38, 135, 0.1)',
                floating: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
            },
            spacing: {
                72: '18rem',
                80: '20rem',
                84: '21rem',
                96: '24rem',
                128: '32rem',
            },
            width: {
                '1/7': '14.28%',
                '2/7': '28.57%',
                '3/7': '42.85%',
                '4/7': '57.14%',
                '5/7': '71.42%',
                '6/7': '85.71%',
            },
            maxWidth: {
                '8xl': '100rem',
                '9xl': '120rem',
            },
            aspectRatio: {
                '4/3': '4 / 3',
                '3/2': '3 / 2',
                '5/3': '5 / 3',
                '16/9': '16 / 9',
                '2/1': '2 / 1',
                golden: '1.618 / 1',
            },
            animation: {
                'spin-slow': 'spin 3s linear infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'fade-up': 'fadeUp 0.5s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeUp: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(20px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
            },
        },
    },
    plugins: [
        forms,
        typography,
        // Custom Nepali Font Utilities
        function ({ addUtilities }) {
            const newUtilities = {
                '.font-nepali': {
                    fontFamily: 'Preeti, Noto Sans Devanagari, sans-serif',
                    fontSize: '16px',
                    lineHeight: '1.6',
                },
                '.font-nepali-sm': {
                    fontFamily: 'Preeti, Noto Sans Devanagari, sans-serif',
                    fontSize: '14px',
                    lineHeight: '1.5',
                },
                '.font-nepali-lg': {
                    fontFamily: 'Preeti, Noto Sans Devanagari, sans-serif',
                    fontSize: '18px',
                    lineHeight: '1.7',
                },
            };
            addUtilities(newUtilities);
        },
        // Glassmorphism Utilities
        function ({ addUtilities }) {
            const glassUtilities = {
                '.glass': {
                    backdropFilter: 'blur(12px)',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    border: '1px solid rgba(255, 255, 255, 0.2)',
                    boxShadow: '0 8px 32px rgba(31, 38, 135, 0.1)',
                },
                '.glass-dark': {
                    backdropFilter: 'blur(12px)',
                    backgroundColor: 'rgba(0, 0, 0, 0.1)',
                    border: '1px solid rgba(0, 0, 0, 0.2)',
                    boxShadow: '0 8px 32px rgba(31, 38, 135, 0.1)',
                },
            };
            addUtilities(glassUtilities);
        },
    ],
};
