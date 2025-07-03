import { createRouter, createWebHistory } from 'vue-router'
import { useUser } from "../composables/useUser";

import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import NotFound from '../views/errors/404.vue'
import DashboardHome from "../views/dashboard/Home.vue";
import Dashboard from "../views/Dashboard.vue";
import Unauthorized from "../views/errors/403.vue";
import Categories from '../views/dashboard/Categories.vue';
import Posts from '../views/dashboard/Posts.vue';

const routes = [
    {
        path: '/admin/login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/admin/register',
        component: Register,
        meta: { guest: true }
    },
    {
        path: '/admin/:pathMatch(.*)*',
        component: NotFound
    },
    {
        path: '/admin/unauthorized',
        component: Unauthorized
    },
    {
        path: '/admin/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                component: DashboardHome,
                name: 'Home'
            },
            {
                path: 'categories',
                component: Categories,
                name: 'Categories',
                meta: {requiredRole: 'admin'}
            },
            // {
            //     path: 'products',
            //     component: Products,
            //     name: 'Products'
            // }
        ]
    },
    // {
    //     path: '/admin/email-verification',
    //     component: () => import('../views/auth/EmailVerify.vue'),
    //     meta: { guest: true }
    // },
    // {
    //     path: '/admin/email-verification-success',
    //     component: () => import('../views/auth/EmailVerifySuccess.vue'),
    //     meta: { guest: true }
    // },
    // {
    //     path: '/admin/email-verification-failed',
    //     component: () => import('../views/auth/EmailVerifyFailure.vue'),
    //     meta: { guest: true }
    // },
    // {
    //     path: '/admin/forgot-password',
    //     component: () => import('../views/auth/ForgotPassword.vue'),
    //     meta: { guest: true }
    // },
    // {
    //     path: '/admin/reset-password',
    //     component: () => import('../views/auth/ResetPassword.vue'),
    //     meta: { guest: true },
    //     props: (route) => ({
    //         token: route.query.token,
    //         email: route.query.email
    //     })
    // }
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
    } else if (to.meta.requiredRole && to.meta.requiredRole !== userRole) {
        next("/admin/unauthorized"); // Redirect to unauthorized page
    } else {
        next();
    }
});

export default router
