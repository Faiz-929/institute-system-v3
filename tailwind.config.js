import { fontFamily } from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#e6f3ff',
          100: '#cce7ff',
          200: '#99cfff',
          300: '#66b7ff',
          400: '#339fff',
          500: '#0087ff',
          600: '#006ccc',
          700: '#005199',
          800: '#003666',
          900: '#001b33',
        },
        secondary: {
          50: '#e6f7f4',
          100: '#ccefe9',
          200: '#99dfd3',
          300: '#66cfbd',
          400: '#33bfa7',
          500: '#00af91',
          600: '#008c74',
          700: '#006957',
          800: '#00463a',
          900: '#00231d',
        },
        success: {
          50: '#e6f7ed',
          100: '#ccefdb',
          200: '#99dfb7',
          300: '#66cf93',
          400: '#33bf6f',
          500: '#00af4b',
          600: '#008c3c',
          700: '#00692d',
          800: '#00461e',
          900: '#00230f',
        },
        danger: {
          50: '#ffe6e6',
          100: '#ffcccc',
          200: '#ff9999',
          300: '#ff6666',
          400: '#ff3333',
          500: '#ff0000',
          600: '#cc0000',
          700: '#990000',
          800: '#660000',
          900: '#330000',
        },
        warning: {
          50: '#fff8e6',
          100: '#fff1cc',
          200: '#ffe399',
          300: '#ffd566',
          400: '#ffc733',
          500: '#ffb900',
          600: '#cc9400',
          700: '#996f00',
          800: '#664a00',
          900: '#332500',
        },
      },
      fontFamily: {
        sans: ['Cairo', 'Tajawal', ...fontFamily.sans],
      },
      boxShadow: {
        'soft': '0 2px 8px rgba(0, 0, 0, 0.08)',
        'medium': '0 4px 16px rgba(0, 0, 0, 0.12)',
        'hard': '0 8px 24px rgba(0, 0, 0, 0.16)',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};