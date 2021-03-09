import Dashboard from '../views/Dashboard.vue';
import Index from '../views/Index.vue';
import Create from '../views/Create.vue';
import Show from '../views/Show.vue';
import Update from '../views/Update.vue';

export default [
    {
        path: '/',
        redirect: {
            name: 'dashboard'
        }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/resources/:resourceKey',
        name: 'index',
        component: Index
    },
    {
        path: '/resources/:resourceKey/create',
        name: 'create',
        component: Create
    },
    {
        path: '/resources/:resourceKey/:resourceId',
        name: 'show',
        component: Show
    },
    {
        path: '/resources/:resourceKey/:resourceId/update',
        name: 'update',
        component: Update
    },
];
