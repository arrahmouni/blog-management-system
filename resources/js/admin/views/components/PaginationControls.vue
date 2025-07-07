<template>
    <div class="flex justify-between items-center mt-4">
        <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 disabled:opacity-50"
        >
            Previous
        </button>
        <span class="text-gray-600"
            >Page {{ currentPage }} of {{ totalPages }}</span
        >
        <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 disabled:opacity-50"
        >
            Next
        </button>
    </div>
</template>

<script setup>
    defineProps({
        currentPage: Number,
        totalPages: Number,
    });

    const emit = defineEmits(["next-page", "prev-page"]);

    const nextPage = () => emit("next-page");
    const prevPage = () => emit("prev-page");
</script>
