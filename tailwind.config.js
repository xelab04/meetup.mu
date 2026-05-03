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
                mono: ['"Space Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                island: {
                    bg:      'oklch(var(--island-bg) / <alpha-value>)',
                    card:    'oklch(var(--island-card) / <alpha-value>)',
                    fg:      'oklch(var(--island-fg) / <alpha-value>)',
                    muted:   'oklch(var(--island-muted) / <alpha-value>)',
                    rule:    'oklch(var(--island-rule) / <alpha-value>)',
                    primary: 'oklch(var(--island-primary) / <alpha-value>)',
                    accent:  'oklch(var(--island-accent) / <alpha-value>)',
                    gold:    'oklch(var(--island-gold) / <alpha-value>)',
                    today:   'oklch(var(--island-today) / <alpha-value>)',
                },
            },
        },
    },
    plugins: [],
};
