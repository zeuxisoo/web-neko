import Gallery from '@/views/home/Gallery.vue';
import Profile from '@/views/home/Profile.vue';
import Pulse from '@/views/home/Pulse.vue';
import NotFound from '@/views/misc/NotFound.vue';
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'pulse',
        component: Pulse,
    },
    {
        path: '/gallery',
        name: 'gallery',
        component: Gallery,
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
    },
    {
        path: '/:catchAll(.*)*',
        name: 'NotFound',
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
