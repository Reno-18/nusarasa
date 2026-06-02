import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'nusarasa-cream': '#f7f3e8',
                'nusarasa-pink': '#fcdada',
                'nusarasa-purple': '#e5dcf4',
                'nusarasa-yellow': '#fef2cb',
                'nusarasa-dark': '#1a1a1a',
            },
            borderRadius: {
                '3xl': '1.5rem',
                '4xl': '2rem',
                'pill': '100px',
            }
        },
    },

    plugins: [forms],
};
