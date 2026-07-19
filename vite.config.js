import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

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
                },
            },
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    build: {
        rolldownOptions: {
            output: {
                codeSplitting: {
                    groups: [
                        {
                            name: 'framework',
                            test: /node_modules\/(vue|vue-router|pinia)/,
                            priority: 10,
                        },
                        {
                            name: 'ui-components',
                            test: /node_modules\/(lucide-vue-next|reka-ui|vue-sonner|vue-it-bigger)/,
                            priority: 9,
                        },
                        {
                            name: 'utils',
                            test: /node_modules\/(es-toolkit|date-fns|clsx|tailwind-merge|fuse\.js|sprintf-js|chevrotain|@vueuse\/core|class-variance-authority|textarea-caret)/,
                            priority: 8,
                        },
                        {
                            name: 'code-mark',
                            test: /node_modules\/(shiki|rehype-(raw|sanitize)|remark-(breaks|gfm))/,
                            priority: 8,
                        },
                    ],
                },
            },
        },
    },
});
