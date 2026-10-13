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
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                heading: ['"Be Vietnam Pro"', 'Inter', 'sans-serif'],
            },
            colors: {
                base: '#FBF7F2',      // nền trang
                surface: '#FFFFFF',   // nền card
                ink: '#2B2220',       // chữ chính trên nền sáng
                cream: '#FFFAF5',     // chữ trên nền cam đào / nền đen
                primary: { DEFAULT: '#E0684B', dark: '#C44E33' }, // Cam đào (Peach Orange)
                accent: '#E9B58A',
                navy: '#344869',
                beige: '#CAA996',
                stone: '#B9B9B9',
                sand: '#F1E5DC',
                mist: '#DCE3EE',
                success: { DEFAULT: '#BFE3CC', ink: '#1F5F3D' },
                warning: { DEFAULT: '#FBE5B6', ink: '#7A5200' },
                danger:  { DEFAULT: '#F6C9C9', ink: '#8A2A2A' },
                info:    { DEFAULT: '#C9E2F4', ink: '#1F4E79' },
            },
        },
    },

    plugins: [forms],
};
