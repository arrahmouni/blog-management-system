<template>
    <div class="comment-section">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                Comments ({{ pagination.total ?? 0 }})
            </h2>
        </div>

        <div class="comment-form mb-12">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div
                        class="bg-gray-200 border-2 border-dashed rounded-full w-12 h-12"
                    />
                </div>
                <div class="flex-grow">
                    <textarea
                        v-model="newComment"
                        class="w-full h-24 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Add a comment..."
                    ></textarea>
                    <div class="mt-3 flex justify-end">
                        <button
                            @click="submitComment"
                            :disabled="!newComment.trim()"
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Post Comment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="comment-list" ref="commentsContainer">
            <CommentItem
                v-for="comment in validComments"
                :key="comment.id"
                :comment="comment"
            />

            <div
                v-if="validComments.length === 0 && !loadingMore"
                class="text-center py-8 text-gray-500"
            >
                No comments yet. Be the first to comment!
            </div>

            <div v-if="loadingMore" class="text-center py-4">
                <i class="fas fa-spinner fa-spin text-indigo-600"></i>
                <span class="ml-2 text-gray-600">Loading more comments...</span>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted, onUnmounted } from "vue";
    import CommentItem from "./CommentItem.vue";

    const props = defineProps({
        comments: {
            type: Array,
            required: true,
            default: () => [],
        },
        pagination: {
            type: Object,
            default: () => ({}),
        },
        postId: {
            type: [Number, String],
            required: true,
        },
        loadingMore: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(["add-comment", "load-more"]);

    const commentsContainer = ref(null);
    const newComment = ref("");

    const validComments = computed(() => {
        return props.comments.filter(
            (comment) =>
                comment &&
                typeof comment === "object" &&
                comment.comment &&
                comment.id
        );
    });

    const submitComment = () => {
        if (newComment.value.trim()) {
            emit("add-comment", {
                content: newComment.value.trim(),
                post_id: props.postId,
            });
            newComment.value = "";
        }
    };

    const handleScroll = () => {
        if (!commentsContainer.value || props.loadingMore) return;

        const container = commentsContainer.value;
        const scrollPosition = container.scrollTop + container.clientHeight;
        const scrollThreshold = container.scrollHeight - 100;

        if (scrollPosition >= scrollThreshold && props.pagination?.has_more_pages) {
            emit("load-more", props.pagination.current_page + 1);
        }
    };

    onMounted(() => {
        if (commentsContainer.value) {
            commentsContainer.value.addEventListener("scroll", handleScroll);
        }
    });

    onUnmounted(() => {
        if (commentsContainer.value) {
            commentsContainer.value.removeEventListener("scroll", handleScroll);
        }
    });
</script>

<style scoped>
    .comment-section {
        margin-top: 3rem;
    }

    .comment-list {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .comment-list::-webkit-scrollbar {
        width: 8px;
    }

    .comment-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .comment-list::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 10px;
    }

    .comment-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
