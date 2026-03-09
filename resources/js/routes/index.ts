import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'index',
        component: () => import('@/views/park/Pulse.vue'),
        alias: '/park',
    },
    {
        path: '/park',
        name: 'park',
        children: [
            {
                path: 'pulse',
                name: 'park.pulse',
                component: () => import('@/views/park/Pulse.vue'),
            },
            {
                path: 'pulse/comment/:id',
                name: 'park.pulse.comment',
                component: () => import('@/views/park/PulseComment.vue'),
            },
            {
                path: 'pulse/bookmark/',
                name: 'park.bookmark',
                component: () => import('@/views/park/Bookmark.vue'),
            },
            {
                path: 'attachment',
                name: 'park.attachment',
                component: () => import('@/views/park/Attachment.vue'),
            },
            {
                path: 'link',
                name: 'park.link',
                component: () => import('@/views/park/Link.vue'),
            },
        ],
    },
    {
        path: '/drift',
        name: 'drift',
        redirect: { name: 'drift.index' },
        children: [
            {
                path: 'index',
                name: 'drift.index',
                component: () => import('@/views/drift/Index.vue'),
            },
        ],
    },
    {
        path: '/settings',
        name: 'settings',
        redirect: { name: 'settings.index' },
        children: [
            {
                path: 'index',
                name: 'settings.index',
                component: () => import('@/views/settings/Index.vue'),
            },
        ],
    },
    {
        path: '/user',
        name: 'user',
        redirect: { name: 'user.account' },
        children: [
            {
                path: 'account',
                name: 'user.account',
                component: () => import('@/views/user/Account.vue'),
            },
        ],
    },
    {
        path: '/:catchAll(.*)*',
        name: 'NotFound',
        component: () => import('@/views/misc/NotFound.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
