import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                // Primary colors from your logo (deep blue)
                primary: {
                    DEFAULT: '#1E3A8A',  // Deep blue
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                    foreground: '#FFFFFF',
                },
                // Accent color (gold from logo)
                accent: {
                    DEFAULT: '#D4AF37',  // Gold
                    50: '#FDF8E7',
                    100: '#F9EDCF',
                    200: '#F3DB9F',
                    300: '#E8C76F',
                    400: '#D4AF37',
                    500: '#B8942D',
                    600: '#9A7A24',
                    700: '#7C611C',
                    foreground: '#1E3A8A',
                },
                // Sidebar colors
                sidebar: {
                    DEFAULT: '#0F172A',         // Dark navy
                    foreground: '#E2E8F0',      // Light gray text
                    accent: '#1E3A8A',          // Primary blue
                    'accent-foreground': '#FFFFFF',
                    border: '#334155',
                },
                // Card and background
                card: '#FFFFFF',
                'card-foreground': '#1E293B',
                background: '#F1F5F9',
                foreground: '#1E293B',
                border: '#E2E8F0',
                muted: {
                    DEFAULT: '#F1F5F9',
                    foreground: '#64748B',
                },
            },
        },
    },
    plugins: [forms],
};
