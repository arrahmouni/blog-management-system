import { createRouter, createWebHistory } from 'vue-router'
import { useUser } from "../composables/useUser";

import Login            from '../views/auth/Login.vue'
import NotFound         from '../views/errors/404.vue'
import DashboardHome    from '../views/dashboard/Home.vue';
import Dashboard        from '../views/Dashboard.vue';
import Unauthorized     from '../views/errors/403.vue';
import Categories       from '../views/dashboard/Categories.vue';
import Posts            from '../views/dashboard/Posts.vue';
import CategoryLogs     from '../views/dashboard/CategoryLogs.vue';
import PostLogs         from '../views/dashboard/PostLogs.vue';
import UpgradeRequests  from '../views/dashboard/UpgradeRequests.vue';

const routes = [
    {
        path        : '/admin/login',
        component   : Login,
        meta        : { guest: true }
    },
    {
        path        : '/admin/:pathMatch(.*)*',
        component   : NotFound
    },
    {
        path        : '/admin/unauthorized',
        component   : Unauthorized
    },
    {
        path        : '/admin/dashboard',
        component   : Dashboard,
        meta        : { requiresAuth: true },
        children    : [
            {
                path        : '',
                component   : DashboardHome,
                name        : 'Home'
            },
            {
                path        : 'categories',
                component   : Categories,
                name        : 'Categories',
                meta        : {requiredRole: 'admin'}
            },
            {
                path        : 'categories/:id/logs',
                name        : 'category-logs',
                component   : CategoryLogs,
                props       : true,
                meta        : {requiredRole: 'admin'}
            },
            {
                path        : 'posts',
                component   : Posts,
                name        : 'Posts',
                meta        : {requiredRole: ['admin', 'writer']}
            },
            {
                path        : 'posts/:id/logs',
                name        : 'post-logs',
                component   : PostLogs,
                props       : true,
                meta        : {requiredRole: ['admin', 'writer']}
            },
            {
                path        : 'upgrade-requests',
                component   : UpgradeRequests,
                name        : 'UpgradeRequests',
                meta        : {requiredRole: 'admin'}
            }
        ]
    },
    {
        path        : '/admin/forgot-password',
        component   : () => import('../views/auth/ForgotPassword.vue'),
        meta        : { guest: true }
    },
    {
        path        : '/admin/reset-password',
        component   : () => import('../views/auth/ResetPassword.vue'),
        meta        : { guest: true },
        props       : (route) => ({
            token: route.query.token,
            email: route.query.email
        })
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const { getRole } = useUser();
    const isAuthenticated = localStorage.getItem("authToken");
    const userRole = getRole();

    if (to.meta.requiresAuth && !isAuthenticated) {
        next("/admin/login");
    }
    else if (
        to.meta.requiredRole &&
        !to.meta.requiredRole.includes(userRole)
    ) {
        next("/admin/unauthorized");
    }
    else if (to.meta.guest) {
        const token = localStorage.getItem('authToken');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                await axios.get('/user');
                next('/admin/dashboard');
            } catch (error) {
                localStorage.removeItem('authToken');
                delete axios.defaults.headers.common['Authorization'];
                next();
            }
        } else {
            next();
        }
    }
    else {
        next();
    }
});

export default router
