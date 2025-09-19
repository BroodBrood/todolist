import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    background: '#FFFFFF', // background, fris en clean
                    accent: '#AEE6FA', // accenten en highlights (rustgevend blauw)
                    primary: '#1E3A8A', // primaire knoppen en header (krachtig donkerblauw)
                    muted: '#64748B', // tekst en secundaire elementen (neutraal grijs)
                    warning: '#FDE68A', // waarschuwingen, deadlines of prioriteitsaccenten (zacht geel)
                },
            },
            container: {
                center: true,
                padding: '1rem',
                screens: {
                    sm: '640px',
                    md: '768px',
                    lg: '1024px',
                    xl: '1280px',
                },
            },
        },
    },
    plugins: [],
};
