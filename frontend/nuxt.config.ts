import Lara from '@primevue/themes/lara';
import { definePreset } from '@primevue/themes';

const MyPreset = definePreset(Lara, {
  semantic: {
    primary: {
        50: '{blue.50}',
        100: '{blue.100}',
        200: '{blue.200}',
        300: '{blue.300}',
        400: '{blue.400}',
        500: '{blue.500}',
        600: '{blue.600}',
        700: '{blue.700}',
        800: '{blue.800}',
        900: '{blue.900}',
        950: '{blue.950}'
    },
    colorScheme: {
        light: {
            primary: {
                color: '{blue.950}',
                inverseColor: '#ffffff',
                hoverColor: '{blue.900}',
                activeColor: '{blue.800}'
            }
        },
        dark: {
            primary: {
                color: '{blue.50}',
                inverseColor: '{blue.950}',
                hoverColor: '{blue.100}',
                activeColor: '{blue.200}'
            },
        }
    }
  }
});
export default defineNuxtConfig({
  hooks: {
    'ready': (nuxt) => {}
  },
  modules: [
    '@primevue/nuxt-module',
    'nuxt-auth-sanctum',
    '@nuxt/icon',
    '@pinia/nuxt',
    '@nuxtjs/i18n',
    'nuxt-zod-i18n',
    '@vite-pwa/nuxt'
  ],
  ssr: false,
  primevue: {
    autoImport: true,
    options: {
      theme: {
        preset: MyPreset,
        options: {
          darkModeSelector: '.dark',
        }
      },
    }
  },
  sanctum: {
    mode: 'cookie',
    baseUrl: (() => {
      const raw = process.env.API_URL || 'http://localhost:8000';
      // Handle relative URLs (e.g. "/api" for same-origin deployment)
      if (raw.startsWith('/')) return `http://localhost${raw}`;
      const u = new URL(raw);
      const path = u.pathname.replace(/\/$/, '');
      return `${u.protocol}//${u.host}${path}`;
    })(),
    redirectIfAuthenticated: true,
    redirectIfUnauthenticated: true,
    redirect: {
      keepRequestedRoute: true,
      onAuthOnly: '/login',
      onGuestOnly: '/',
      onLogout: '/login'
    },
    globalMiddleware: {
      enabled: true,
      allow404WithoutAuth: true,
    },
    logLevel: 1,
  },
  css: ['~/assets/css/main.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  nitro: {
    alias: {
      'unenv/runtime': 'unenv'
    }
  },
  pwa: {
    workbox: {
      navigateFallback: '/index.html',
      globPatterns: ['**/*.{js,css,html,png,svg,ico}'],
      runtimeCaching: [
        {
          urlPattern: (() => {
            const esc = (s: string) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const raw = process.env.API_URL || 'http://localhost:8000';
            if (raw.startsWith('/')) {
              // Relative URL: same-origin deployment — match /api/... paths
              return new RegExp(`^${esc(raw.replace(/\/$/, ''))}\\/.*`, 'i');
            }
            const u = new URL(raw);
            const origin = `${u.protocol}//${u.host}`;
            const path = u.pathname.replace(/\/$/, '');
            return new RegExp(`^${esc(origin)}${esc(path)}\\/.*`, 'i');
          })(),
          handler: 'NetworkFirst',
          options: {
            cacheName: 'api-cache',
            expiration: {
              maxEntries: 100,
              maxAgeSeconds: 60 * 60 * 24 // 24 hours
            }
          }
        }
      ]
    },
    registerType: 'autoUpdate',
    includeAssets: ['favicon.ico', 'apple-touch-icon.png', 'masked-icon.svg'],
    manifest: {
      name: 'Asisten Notaris',
      short_name: 'AsistenNotaris',
      description: 'Aplikasi Manajemen Kantor Notaris',
      theme_color: '#172554',
      background_color: '#ffffff',
      display: 'standalone'
    }
  },
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true }
});
