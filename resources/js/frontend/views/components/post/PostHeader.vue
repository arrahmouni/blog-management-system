<template>
    <div class="post-header">
        <div class="relative">
            <div
                v-if="post.image_url"
                class="post-image w-full h-96 bg-cover bg-center rounded-xl"
                :style="{ backgroundImage: `url('${post.image_url}')` }"
            ></div>
            <div
                v-else
                class="post-image w-full h-96 rounded-xl"
                :class="randomGradient"
            ></div>

            <div class="absolute bottom-4 left-4 flex flex-wrap gap-2">
                <span
                    v-for="category in post.categories"
                    :key="category.id"
                    class="px-3 py-1 text-white text-sm font-semibold rounded-full"
                    :class="categoryColor(category.title)"
                >
                    {{ category.title }}
                </span>
            </div>
        </div>

        <div class="mt-8">
            <h1 class="text-4xl font-extrabold text-gray-900">
                {{ post.title }}
            </h1>

            <div class="mt-6 flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="bg-gray-200 border-2 border-dashed rounded-full w-14 h-14"
                    />
                </div>
                <div class="ml-4">
                    <p class="text-lg font-medium text-gray-900">
                    {{ post.user?.full_name || 'Unknown author' }}
                    </p>
                    <p class="text-gray-500">
                        {{ post.published_at }} ·
                        {{ calculateReadTime(post.body) }} min read
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        post: {
            type: Object,
            required: true,
        },
    });

    const gradients = [
        "bg-gradient-to-r from-blue-400 to-indigo-600",
        "bg-gradient-to-r from-green-400 to-teal-500",
        "bg-gradient-to-r from-amber-400 to-orange-500",
        "bg-gradient-to-r from-pink-400 to-rose-600",
        "bg-gradient-to-r from-purple-400 to-fuchsia-600",
    ];

    const randomGradient = computed(() => {
        return gradients[Math.floor(Math.random() * gradients.length)];
    });

    const calculateReadTime = (text) => {
        const words = text.split(/\s+/).length;
        return Math.ceil(words / 200);
    };

    const categoryColor = (category) => {
        const colors = {
            Technology: "bg-indigo-600",
            Health: "bg-teal-600",
            Travel: "bg-amber-600",
            "Food & Cooking": "bg-rose-600",
            Entertainment: "bg-purple-600",
            "Sports & Fitness": "bg-emerald-600",
            Parenting: "bg-pink-600",
            Economy: "bg-blue-600",
            Environment: "bg-green-600",
        };

        return colors[category] || "bg-gray-600";
    };
</script>

<style scoped>
    .post-header {
        margin-bottom: 3rem;
    }

    .post-image {
        transition: transform 0.3s ease;
    }

    .post-image:hover {
        transform: scale(1.01);
    }
</style>
