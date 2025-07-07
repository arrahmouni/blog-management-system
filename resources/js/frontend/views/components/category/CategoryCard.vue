<template>
    <div
        class="category-item bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 cursor-pointer"
    >
        <div
            class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
            :class="iconBackgroundClass"
        >
            <i :class="[iconClass, iconColorClass, 'text-2xl']"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ category.title }}</h3>
        <p class="mt-2 text-gray-600">
            lorem ipsum dolor sit amet consectetur adipisicing elit
        </p>
        <div class="mt-4 text-indigo-600 font-medium">
            {{ category.post_count }} Posts
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        category: {
            type: Object,
            required: true,
        },
    });

    const iconClass = "fas fa-folder";
    const iconBackgroundClass = "bg-indigo-100";
    const iconColorClass = "text-indigo-600";
</script>
