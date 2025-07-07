<template>
    <div
        class="post-card bg-white rounded-xl shadow-md overflow-hidden card-hover"
    >
        <div class="relative">
            <!-- Dynamic Image -->
            <div
                v-if="post.image_url"
                class="post-image w-full h-48 bg-cover bg-center"
                :style="{ backgroundImage: `url('${post.image_url}')` }"
            ></div>
            <div
                v-else
                class="post-image w-full h-48"
                :class="randomGradient"
            ></div>

            <!-- Category Badge (using first category) -->
            <div class="absolute top-4 left-4" v-if="post.categories.length">
                <span
                    v-for="(category) in visibleCategories"
                    :key="category.id"
                    class="px-2 py-1 text-white text-xs font-semibold rounded-full whitespace-nowrap mr-2"
                    :class="categoryColor(category.title)"
                    :title="category.title"
                >
                {{ truncateCategory(category.title) }}
                </span>

                <!-- "+X More" Badge for extra categories -->
                <span
                    v-if="hiddenCategoriesCount > 0"
                    class="px-2 py-1 bg-gray-700 text-white text-xs font-semibold rounded-full cursor-help"
                    :title="hiddenCategoriesTitles"
                >
                +{{ hiddenCategoriesCount }} more
                </span>
            </div>
        </div>

        <div class="p-6">
            <!-- Author Info -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div
                        class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10"
                    />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">
                        {{ post.user.full_name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ post.created_at }} ·
                        {{ calculateReadTime(post.body) }} min read
                    </p>
                </div>
            </div>

            <!-- Post Content -->
            <div class="mt-4">
                <router-link
                    :to="`/post/${post.slug}`"
                    class="text-xl font-bold text-gray-900 hover:text-indigo-600"
                >
                    {{ post.title }}
                </router-link>
                <p class="mt-3 text-gray-500 line-clamp-2">{{ post.body }}</p>
            </div>

            <!-- Post Stats -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="far fa-comment text-gray-400 ml-4 mr-1"></i>
                    <span class="text-sm text-gray-500">{{ post.comments_count }}</span>
                </div>
                <router-link
                    :to="`/post/${post.slug}`"
                    class="text-indigo-600 hover:text-indigo-800 font-medium text-sm"
                >
                    Read More →
                </router-link>
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
        maxVisible: {
            type: Number,
            default: 2  // Show max 2 categories before showing "+X more"
        }
    });

    // Calculate visible and hidden categories
    const visibleCategories = computed(() => {
        return props.post.categories.slice(0, props.maxVisible);
    });

    const hiddenCategoriesCount = computed(() => {
        return Math.max(0, props.post.categories.length - props.maxVisible);
    });

    const hiddenCategoriesTitles = computed(() => {
        return props.post.categories
        .slice(props.maxVisible)
        .map(c => c.title)
        .join(', ');
    });

    // Truncate long category names
    const truncateCategory = (title) => {
        if (title.length > 12) {
            return title.substring(0, 10) + '..';
        }
        return title;
    };


    // Random gradient for posts without images
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

    // Category color mapping
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
        };

        return colors[category] || "bg-gray-600";
    };
</script>

<style scoped>
    .post-image {
        transition: transform 0.5s ease;
    }
    .post-card:hover .post-image {
        transform: scale(1.05);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
