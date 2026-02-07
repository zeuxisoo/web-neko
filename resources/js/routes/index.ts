import NotFound from '@/views/misc/NotFound.vue';
import Gallery from '@/views/park/Gallery.vue';
import Pulse from '@/views/park/Pulse.vue';
import PulseComment from '@/views/park/PulseComment.vue';
import Account from '@/views/user/Account.vue';
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'index',
        component: Pulse,
        alias: '/park',
    },
    {
        path: '/park',
        name: 'park',
        children: [
            {
                path: 'pulse',
                name: 'park.pulse',
                component: Pulse,
            },
            {
                path: 'pulse/comment/:id',
                name: 'park.pulse.comment',
                component: PulseComment,
            },
            {
                path: 'gallery',
                name: 'park.gallery',
                component: Gallery,
            },
        ],
    },
    {
        path: '/user',
        name: 'user',
        children: [
            {
                path: 'account',
                name: 'user.account',
                component: Account,
            },
        ],
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
