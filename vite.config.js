import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.jsx",
            refresh: true,
        }),
        react(),
    ],
    build: {
        chunkSizeWarningLimit: 600,
        rollupOptions: {
            output: {
                manualChunks: {
                    'react-vendor': ['react', 'react-dom'],
                    'ui-libs': ['sweetalert2', 'react-hot-toast', 'react-select', 'react-datepicker'],
                    'http': ['axios'],
                },
            },
        },
    },
});
