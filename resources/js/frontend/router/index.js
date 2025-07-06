import { createRouter, createWebHistory } from 'vue-router'
import { useUser }      from "../../admin/composables/useUser";
import Home             from '../views/Home.vue';
import PostDetails      from '../views/components/post/PostDetails.vue';
import Login            from '../views/auth/Login.vue';
import Register         from '../views/auth/Register.vue'
import NotFound         from '../views/errors/404.vue'
import Unauthorized     from '../views/errors/403.vue'
import ForgotPassword   from '../views/auth/ForgotPassword.vue';
import ResetPassword    from '../views/auth/ResetPassword.vue';

const routes = [
    {
        path        : '/:pathMatch(.*)*',
        component   : NotFound
    },
    {
        path        : '/unauthorized',
        component   : Unauthorized
    },
    {
        path        : '/',
        component   : Home,
    },
    {
        path        : '/post/:slug',
        component   : PostDetails,
        name        : 'PostDetails',
        props       : true,
    },
    {
        path        : '/login',
        component   : Login,
        name        : 'Login',
        meta        : { guest: true }
    },
    {
        path        : '/register',
        component   : Register,
        name        : 'Register',
        meta        : { guest: true }
    },
        {
        path        : '/forgot-password',
        component   : ForgotPassword,
        meta        : { guest: true }
    },
    {
        path        : '/reset-password',
        component   : ResetPassword,
        meta        : { guest: true },
        props       : (route) => ({
            token   : route.query.token,
            email   : route.query.email
        })
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    const { getRole } = useUser();
    const isAuthenticated = localStorage.getItem("authToken");
    const userRole = getRole();

    if (to.meta.requiresAuth && !isAuthenticated) {
        next("/login");
    }
    else if (
        to.meta.requiredRole &&
        !to.meta.requiredRole.includes(userRole)
    ) {
        next("/unauthorized");
    } else if (to.meta.guest) {
        const token = localStorage.getItem('authToken');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            try {
                await axios.get('/user');
                next('/');
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
