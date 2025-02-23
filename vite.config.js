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
        outDir: 'public/build', // Genera el build en esta carpeta
        manifest: true,         // Genera manifest.json
        emptyOutDir: true,      // Limpia el directorio antes de construir
        base: '/build/',        // Asegúrate de que las rutas estén configuradas correctamente
    }
});
