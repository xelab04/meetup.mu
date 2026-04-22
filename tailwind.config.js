import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                serif: ['"Instrument Serif"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                island: {
                    bg: '#f3ebdc',
                    'bg-dark': '#141613',
                    card: '#fbf5e7',
                    'card-dark': '#1d1f1c',
                    fg: '#1f1a15',
                    'fg-dark': '#f2ead8',
                    muted: '#7d7263',
                    'muted-dark': '#928a7c',
                    rule: '#d5c8ae',
                    'rule-dark': '#2a2b27',
                    primary: '#a84137',
                    'primary-dark': '#d96e5b',
                    accent: '#2a6e5a',
                    'accent-dark': '#4ea890',
                    gold: '#b88a2a',
                    'gold-dark': '#e8c268',
                    today: '#dc2626',
                    'today-dark': '#f43f5e',
                },
            },
        },
    },
    plugins: [],
};
