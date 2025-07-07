<template>
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Popular Categories
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Discover content based on your interests
                </p>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <CategoryCard
                    v-for="category in categories"
                    :key="category.id"
                    :category="category"
                />
            </div>

            <div class="mt-12 text-center">
                <a
                    href="#"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                >
                    View All Categories
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
    import CategoryCard from "../category/CategoryCard.vue";

    const props = defineProps({
        categories: {
            type: Array,
            required: true,
        },
    });
</script>
