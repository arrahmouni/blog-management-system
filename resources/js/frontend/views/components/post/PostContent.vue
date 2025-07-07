<template>
  <div class="post-content">
    <div v-if="formattedContent.length" class="prose prose-lg max-w-none">
      <p v-for="(paragraph, index) in formattedContent" :key="index" class="mb-4">
        {{ paragraph }}
      </p>
    </div>
    <div v-else class="text-center py-8 text-gray-500">
      Loading content...
    </div>

    <div class="mt-12 flex items-center justify-between border-t border-gray-200 pt-6">
      <div class="flex space-x-4">
        <button class="text-gray-500 hover:text-indigo-600">
          <i class="fab fa-twitter"></i>
        </button>
        <button class="text-gray-500 hover:text-indigo-600">
          <i class="fab fa-facebook"></i>
        </button>
        <button class="text-gray-500 hover:text-indigo-600">
          <i class="fab fa-linkedin"></i>
        </button>
        <button class="text-gray-500 hover:text-indigo-600">
          <i class="fas fa-link"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        post: {
            type: Object,
            required: true
        }
    });

    const formattedContent = computed(() => {
        if (!props.post || !props.post.body) return [];
        return props.post.body.split(/\n{2,}/);
    });
</script>

<style scoped>
    .post-content {
        padding-bottom: 3rem;
        border-bottom: 1px solid #e5e7eb;
    }
</style>
