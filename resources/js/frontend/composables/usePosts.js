import { ref } from "vue";
import axios from "axios";

export const usePosts = () => {
    const posts = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchPosts = async (params = {}) => {
        try {
            loading.value = true;
            const response = await axios.get("posts-list", { params });
            posts.value = response.data.data.data;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Failed to fetch posts";
        } finally {
            loading.value = false;
        }
    };

    return {
        posts,
        loading,
        error,
        fetchPosts,
    };
}
