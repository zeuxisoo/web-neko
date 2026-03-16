import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                }
            }
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    framework: ['vue', 'vue-router', 'pinia'],
                    'ui-components': [
                        'lucide-vue-next',
                        'reka-ui',
                        'vue-sonner',
                        'vue-it-bigger',
                    ],
                    utils: [
                        'es-toolkit',
                        'date-fns',
                        'clsx',
                        'tailwind-merge',
                        'fuse.js',
                        'sprintf-js',
                        'chevrotain',
                        '@vueuse/core',
                        'class-variance-authority',
                        'textarea-caret',
                        'shiki',
                    ],
                },
            },
        },
    },
});
