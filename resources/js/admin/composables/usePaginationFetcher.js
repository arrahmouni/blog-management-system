import { ref } from "vue";
import axios from "axios";

export default function usePaginationFetcher(apiPath) {
    const items = ref([]);
    const isLoading = ref(false);
    const error = ref(null);
    const currentPage = ref(1);
    const totalPages = ref(1);

    const fetchData = async (page = 1) => {
        try {
            isLoading.value = true;
            error.value = null;

            const response = await axios.get(`/${apiPath}`, {
                params: { page },
            });
            items.value = response.data.data.data;
            currentPage.value = response.data.data.paginate.current_page;
            totalPages.value = response.data.data.paginate.last_page;
        } catch (err) {
            error.value = "Failed to fetch data. Please try again.";
            console.error(err);
        } finally {
            isLoading.value = false;
        }
    };

    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++;
            fetchData(currentPage.value);
        }
    };

    const prevPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--;
            fetchData(currentPage.value);
        }
    };

    return {
        items,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    };
}
