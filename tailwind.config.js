export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'hsl(211, 67%, 33%)',
          foreground: 'hsl(0, 0%, 100%)',
        },
        secondary: {
          DEFAULT: 'hsl(211, 53%, 63%)',
          foreground: 'hsl(0, 0%, 100%)',
        },
        accent: {
          DEFAULT: 'hsl(211, 53%, 63%)',
          foreground: 'hsl(0, 0%, 100%)',
        },
        muted: {
          DEFAULT: 'hsl(211, 30%, 95%)',
          foreground: 'hsl(211, 20%, 50%)',
        },
        border: 'hsl(211, 30%, 90%)',
        sidebar: {
          DEFAULT: 'hsl(211, 67%, 33%)',
          foreground: 'hsl(0, 0%, 100%)',
        },
      },
    },
  },
}
