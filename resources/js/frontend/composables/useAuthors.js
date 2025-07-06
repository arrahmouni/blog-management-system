import { ref } from "vue";
import axios from "axios";

export const useAuthors = () => {
    const authors = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchAuthors = async (params = {}) => {
        try {
            loading.value = true;
            const response = await axios.get("authors-list", { params });
            authors.value = response.data.data.data;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Failed to fetch authors";
        } finally {
            loading.value = false;
        }
    };

    return {
        authors,
        loading,
        error,
        fetchAuthors,
    };
}
