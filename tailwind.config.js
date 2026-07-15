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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#fdf3f4',
                    100: '#fbe8ea',
                    200: '#f5cdd3',
                    300: '#eda6b0',
                    400: '#e17385',
                    500: '#cf4a62',
                    600: '#a32638',
                    700: '#8b1e2f',
                    800: '#741b29',
                    900: '#631a26',
                    950: '#370a11',
                },
                ink: {
                    DEFAULT: '#18181b',
                    soft: '#27272a',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 1px 6px -1px rgb(0 0 0 / 0.06)',
                'card-hover': '0 4px 12px -2px rgb(0 0 0 / 0.10), 0 2px 6px -2px rgb(0 0 0 / 0.06)',
            },
        },
    },

    plugins: [forms],
};
