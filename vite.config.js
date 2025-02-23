import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: 'public', // 👈 Añade esto
        }),
    ],
    build: {
        outDir: 'public/build', // Asegura que Vite genere en /public/build
        manifest: true,
    },
});
