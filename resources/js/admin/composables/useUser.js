// composables/useUser.js
import { ref, computed } from "vue";

const authToken = ref(localStorage.getItem("authToken"));
const userRole = ref(localStorage.getItem("userRole"));

export const useUser = () => {
    const isAuthenticated = computed(() => !!authToken.value);

    const isAdmin = computed(() => userRole.value === "admin");
    const isWriter = computed(() => userRole.value === "writer");
    const isUser = computed(() => userRole.value === "user");

    const getRole = () => userRole.value;

    const login = (token, role) => {
        localStorage.setItem("authToken", token);
        localStorage.setItem("userRole", role);
        authToken.value = token;
        userRole.value = role;
    };

    const logout = () => {
        localStorage.removeItem("authToken");
        localStorage.removeItem("userRole");
        authToken.value = null;
        userRole.value = null;
    };

    return {
        authToken,
        userRole,
        isAuthenticated,
        isAdmin,
        isWriter,
        isUser,
        getRole,
        login,
        logout,
    };
};
