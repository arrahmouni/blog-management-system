import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/admin/app.js', 'resources/js/frontend/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                base: null,
                includeAbsolute: false,
                },
            },
        }),
    ],
});
