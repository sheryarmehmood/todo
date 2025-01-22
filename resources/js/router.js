import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import Tasks from './components/Tasks.vue';

const routes = [
    { path: '/', component: Home, name: 'Home' },
    { path: '/tasks', component: Tasks, name: 'Tasks' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
