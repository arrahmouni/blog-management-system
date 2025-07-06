import { computed } from "vue";

export const useUser = () => {
    const getRole = () => localStorage.getItem("userRole");

    const isAdmin = computed(() => getRole() === "admin");
    const isWriter = computed(() => getRole() === "writer");
    const isUser = computed(() => getRole() === "user");

    return { getRole, isAdmin, isWriter, isUser };
};
