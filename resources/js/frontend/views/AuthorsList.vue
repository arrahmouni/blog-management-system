<!-- views/posts/AuthorsList.vue -->
<template>
    <div class="py-16 bg-gray-50 authors-list">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-10">
                All Authors
            </h1>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <AuthorCard v-for="author in authors" :key="author.id" :author="author" />
            </div>

            <div v-if="isLoading" class="mt-10 text-center text-indigo-500">
                <i class="fas fa-spinner fa-spin text-xl"></i> Loading more
                authors...
            </div>

            <div v-if="noMoreAuthors" class="text-center mt-10 text-gray-500">
                No more authors.
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount  } from "vue";
    import AuthorCard from "../views/components/author/AuthorCard.vue";
    import axios from "axios";

    const authors = ref([]);
    const page = ref(1);
    const isLoading = ref(false);
    const noMoreAuthors = ref(false);

    const loadAuthors = async () => {
        if (isLoading.value || noMoreAuthors.value) return;
        isLoading.value = true;

        try {
            const response = await axios.get(`/authors-list?page=${page.value}`);
            const data = response.data.data.data;

            if (data.length === 0) {
                noMoreAuthors.value = true;
            } else {
                authors.value.push(...data);
                page.value++;
            }
        } catch (err) {
            console.error("Failed to load authors", err);
        } finally {
            isLoading.value = false;
        }
    };

    const handleScroll = () => {
        const nearBottom =
            window.innerHeight + window.scrollY >= document.body.offsetHeight - 300;
        if (nearBottom) loadAuthors();
    };

    onMounted(() => {
        loadAuthors();
        window.addEventListener("scroll", handleScroll);
    });

    onBeforeUnmount(() => {
        window.removeEventListener("scroll", handleScroll);
    });
</script>

<style scoped>
    .authors-list{
        margin-top: 50px;
    }
</style>
