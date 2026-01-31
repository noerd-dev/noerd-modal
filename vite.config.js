import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/noerd-modal.js'],
            publicDirectory: 'dist',
            buildDirectory: 'build',
            hotFile: '../../public/vendor/noerd-modal/hot',
            refresh: ['resources/views/**'],
        }),
    ],
});
