<template>
    <div>
        <HeroSection />
        <FeaturedPosts :posts="featuredPosts"/>
        <CategoriesSection :categories="categories"/>
        <TopAuthors :authors="authors"/>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import HeroSection from '../views/components/home/HeroSection.vue';
    import FeaturedPosts from '../views/components/home/FeaturedPosts.vue';
    import CategoriesSection from './components/home/CategoriesSection.vue';
    import TopAuthors from './components/home/TopAuthors.vue';
    import { usePosts } from '../composables/usePosts';
    import { useCategories } from '../composables/useCategories';
    import { useAuthors } from '../composables/useAuthors';

    const { posts: featuredPosts, fetchPosts } = usePosts();
    const { categories, fetchCategories } = useCategories();
    const { authors, fetchAuthors } = useAuthors();

    onMounted(async () => {
        await fetchPosts({ featured: true, limit: 3 });
        await fetchCategories({limit : 4});
        await fetchAuthors({limit: 3});
    });
</script>
