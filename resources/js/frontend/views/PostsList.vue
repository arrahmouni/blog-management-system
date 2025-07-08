<!-- views/posts/PostsList.vue -->
<template>
    <div class="py-16 bg-gray-50 posts-list">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-10">
                All Posts
            </h1>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <PostCard v-for="post in posts" :key="post.id" :post="post" />
            </div>

            <div v-if="isLoading" class="mt-10 text-center text-indigo-500">
                <i class="fas fa-spinner fa-spin text-xl"></i> Loading more
                posts...
            </div>

            <div v-if="noMorePosts" class="text-center mt-10 text-gray-500">
                No more posts.
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount, watch } from "vue";
    import PostCard from "../views/components/post/PostCard.vue";
    import axios from "axios";
    import { useRoute } from "vue-router";

    const posts         = ref([]);
    const page          = ref(1);
    const isLoading     = ref(false);
    const noMorePosts   = ref(false);
    const route         = useRoute();

    const props = defineProps({
        categorySlug: String,
        authorId: [String, Number],
    });

    const loadPosts = async () => {
        if (isLoading.value || noMorePosts.value) return;
        isLoading.value = true;

        try {
            const response = await axios.get('/posts-list', {
                params: {
                    page        : page.value,
                    category    : props.categorySlug,
                    author      : props.authorId,
                }
            });
            const data = response.data.data.data;

            if (data.length === 0) {
                noMorePosts.value = true;
            } else {
                posts.value.push(...data);
                page.value++;
            }
        } catch (err) {
            console.error("Failed to load posts", err);
        } finally {
            isLoading.value = false;
        }
    };

    const handleScroll = () => {
        const nearBottom =
            window.innerHeight + window.scrollY >= document.body.offsetHeight - 300;
        if (nearBottom) loadPosts();
    };

    const resetList = () => {
        posts.value = [];
        page.value = 1;
        noMorePosts.value = false;
        loadPosts();
    };

    onMounted(() => {
        resetList();
        window.addEventListener("scroll", handleScroll);
    });

    onBeforeUnmount(() => {
        window.removeEventListener("scroll", handleScroll);
    });

    watch(() => route.params.categorySlug, (newSlug) => {
        categorySlug.value = newSlug;
        resetList();
    });
</script>

<style scoped>
    .posts-list{
        margin-top: 50px;
    }
</style>
