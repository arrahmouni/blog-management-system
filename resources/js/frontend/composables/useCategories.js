import { ref } from "vue";
import axios from "axios";

export const useCategories = () => {
    const categories = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchCategories = async (params = {}) => {
        try {
            loading.value = true;
            const response = await axios.get("categories-list", { params });
            categories.value = response.data.data.data;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Failed to fetch categories";
        } finally {
            loading.value = false;
        }
    };

    return {
        categories,
        loading,
        error,
        fetchCategories,
    };
}
