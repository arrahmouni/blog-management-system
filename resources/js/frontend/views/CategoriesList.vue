<!-- views/posts/CategoriesList.vue -->
<template>
    <div class="py-16 bg-gray-50 categories-list">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-10">
                All Categories
            </h1>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <CategoryCard v-for="category in categories" :key="category.id" :category="category" />
            </div>

            <div v-if="isLoading" class="mt-10 text-center text-indigo-500">
                <i class="fas fa-spinner fa-spin text-xl"></i> Loading more
                categories...
            </div>

            <div v-if="noMoreCategories" class="text-center mt-10 text-gray-500">
                No more categories.
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount  } from "vue";
    import CategoryCard from "../views/components/category/CategoryCard.vue";
    import axios from "axios";

    const categories = ref([]);
    const page = ref(1);
    const isLoading = ref(false);
    const noMoreCategories = ref(false);

    const loadCategories = async () => {
        if (isLoading.value || noMoreCategories.value) return;
        isLoading.value = true;

        try {
            const response = await axios.get(`/categories-list?page=${page.value}`);
            const data = response.data.data.data;

            if (data.length === 0) {
                noMoreCategories.value = true;
            } else {
                categories.value.push(...data);
                page.value++;
            }
        } catch (err) {
            console.error("Failed to load categories", err);
        } finally {
            isLoading.value = false;
        }
    };

    const handleScroll = () => {
        const nearBottom =
            window.innerHeight + window.scrollY >= document.body.offsetHeight - 300;
        if (nearBottom) loadCategories();
    };

    onMounted(() => {
        loadCategories();
        window.addEventListener("scroll", handleScroll);
    });

    onBeforeUnmount(() => {
        window.removeEventListener("scroll", handleScroll);
    });
</script>

<style scoped>
    .categories-list{
        margin-top: 50px;
    }
</style>
