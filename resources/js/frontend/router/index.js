import { createRouter, createWebHistory } from 'vue-router'
import { useUser } from "../../admin/composables/useUser";
import Home from '../views/Home.vue';
import PostDetails from '../views/components/post/PostDetails.vue';

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/post/:slug',
        component: PostDetails,
        name: 'PostDetails',
        props: true
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
        next("/login");
    }
    else if (
        to.meta.requiredRole &&
        !to.meta.requiredRole.includes(userRole)
    ) {
        next("/admin/unauthorized");
    } else {
        next();
    }
});

export default router
