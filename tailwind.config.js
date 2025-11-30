// tailwind.config.js
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        background: 'hsl(0, 0%, 100%)',
        foreground: 'hsl(211, 67%, 20%)',
        card: 'hsl(0, 0%, 100%)',
        'card-foreground': 'hsl(211, 67%, 20%)',
        primary: 'hsl(211, 67%, 33%)',
        'primary-foreground': 'hsl(0, 0%, 100%)',
        secondary: 'hsl(211, 53%, 63%)',
        'secondary-foreground': 'hsl(0, 0%, 100%)',
        muted: 'hsl(211, 30%, 95%)',
        'muted-foreground': 'hsl(211, 20%, 50%)',
        accent: 'hsl(211, 53%, 63%)',
        'accent-foreground': 'hsl(0, 0%, 100%)',
        border: 'hsl(211, 30%, 90%)',
        input: 'hsl(211, 30%, 90%)',
        ring: 'hsl(211, 67%, 33%)',
      },
      borderRadius: {
        DEFAULT: '0.5rem',
      },
    },
  },
  plugins: [],
}
