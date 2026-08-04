import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        ui: {
          primary: 'var(--ui-primary)',
          'primary-hover': 'var(--ui-primary-hover)',
          'primary-soft': 'var(--ui-primary-soft)',
          bg: 'var(--ui-bg)',
          surface: 'var(--ui-surface)',
          border: 'var(--ui-border)',
          'text-primary': 'var(--ui-text-primary)',
          'text-secondary': 'var(--ui-text-secondary)',
          success: 'var(--ui-success)',
          'success-soft': 'var(--ui-success-soft)',
          warning: 'var(--ui-warning)',
          'warning-soft': 'var(--ui-warning-soft)',
          danger: 'var(--ui-danger)',
          'danger-soft': 'var(--ui-danger-soft)',
          info: 'var(--ui-info)',
          'info-soft': 'var(--ui-info-soft)',
          muted: 'var(--ui-muted)',
        }
      },
      spacing: {
        'ui-4': 'var(--ui-space-4)',
        'ui-8': 'var(--ui-space-8)',
        'ui-12': 'var(--ui-space-12)',
        'ui-16': 'var(--ui-space-16)',
        'ui-20': 'var(--ui-space-20)',
        'ui-24': 'var(--ui-space-24)',
        'ui-32': 'var(--ui-space-32)',
        'ui-40': 'var(--ui-space-40)',
        'ui-48': 'var(--ui-space-48)',
        'ui-64': 'var(--ui-space-64)',
      },
      borderRadius: {
        'ui-sm': 'var(--ui-radius-sm)',
        'ui-md': 'var(--ui-radius-md)',
        'ui-lg': 'var(--ui-radius-lg)',
        'ui-xl': 'var(--ui-radius-xl)',
      },
      boxShadow: {
        'ui-sm': 'var(--ui-shadow-sm)',
        'ui-md': 'var(--ui-shadow-md)',
        'ui-lg': 'var(--ui-shadow-lg)',
      },
      transitionDuration: {
        'ui-fast': 'var(--ui-transition-fast)',
        'ui-normal': 'var(--ui-transition-normal)',
      }
    },
  },
  plugins: [
    forms,
  ],
}


