import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build',
        }),
    ],
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
        base: '/build/',
    },
    define: {
        'process.env.NODE_ENV': '"production"' // 🔑 Esta línea es la que falta
    }
});
