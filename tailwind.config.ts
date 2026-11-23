import type { Config } from 'tailwindcss'

const config: Config = {
  darkMode: 'class',
  content: ['./src/**/*.{js,ts,jsx,tsx,mdx}'],
  theme: {
    extend: {
      colors: {
        ground:    '#0D1526',
        surface:   '#16233F',
        surface2:  '#1E2F52',
        ink:       '#EEE8DE',
        muted:     '#8A9BB8',
        gold:      '#F4C63D',
        'gold-dim':'#C49B1E',
        ember:     '#E55243',
        sage:      '#4ADE80',
      },
      fontFamily: {
        display: ['var(--font-fraunces)', 'serif'],
        body:    ['var(--font-jakarta)', 'sans-serif'],
        mono:    ['var(--font-mono)', 'monospace'],
      },
      borderRadius: {
        DEFAULT: '0.5rem',
      },
      animation: {
        'pulse-slow':   'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'spin-slow':    'spin 8s linear infinite',
        'countdown':    'countdown 1s ease-in-out',
        'float':        'float 6s ease-in-out infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%':      { transform: 'translateY(-12px)' },
        },
      },
    },
  },
  plugins: [],
}

export default config
