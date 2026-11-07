import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3 aún usa @import y funciones de color heredadas.
                quietDeps: true,
            },
        },
    },
    build: {
        // Solo se empaquetan los subconjuntos de fuente que el navegador pida
        // (unicode-range); se evita incrustar fuentes como base64.
        assetsInlineLimit: 0,
    },
});
