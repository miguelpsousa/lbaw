import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/projects/createProject.js',
                'resources/js/projects/seeTeamMembers.js',
                'resources/js/projects/searchTask.js',
                'resources/js/search.js',
                'resources/js/tasks/editComment.js',
                'resources/js/projects/forumMessages.js',
                'resources/js/projects/favorites.js',
                'resources/js/projects/favoriteProject.js',
            ],
            refresh: true,
        }),
    ],
});

