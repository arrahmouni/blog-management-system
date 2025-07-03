import { computed } from "vue";

export const useUser = () => {
    const getRole = () => localStorage.getItem("userRole");

    const isAdmin = computed(() => getRole() === "admin");
    const isWriter = computed(() => getRole() === "writer");

    return { getRole, isAdmin, isWriter };
};
