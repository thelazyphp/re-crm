import Dashboard from '../views/Dashboard.vue';
import Index from '../views/Index.vue';
import Create from '../views/Create.vue';
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
        path: '/resources/:resourceName',
        name: 'index',
        component: Index
    },
    {
        path: '/resources/:resourceName/create',
        name: 'create',
        component: Create
    },
    {
        path: '/resources/:resourceName/:resourceId/update',
        name: 'update',
        component: Update
    },
];
