<template>
  <div class="post-details">
    <PostHeader v-if="post && post.user" :post="post" />
    <PostContent v-if="post" :post="post" />
    <CommentSection
      v-if="post"
      :comments="comments"
      :pagination="pagination"
      :post-id="post.id"
      :loading-more="loadingMoreComments"
      @add-comment="handleAddComment"
      @load-more="loadMoreComments"
    />

    <div v-if="isLoading" class="text-center py-12">
      <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
      <p class="mt-4 text-lg text-gray-600">Loading post...</p>
    </div>

    <div v-if="error" class="text-center py-12">
      <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
      <p class="mt-4 text-lg text-gray-600">{{ error }}</p>
      <button @click="loadData" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md">
        Retry
      </button>
    </div>
  </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute } from 'vue-router';
    import PostHeader from './PostHeader.vue';
    import PostContent from './PostContent.vue';
    import CommentSection from './CommentSection.vue';
    import { fetchPostDetails, fetchPostComments, addComment } from '../../../api/posts';
    import { useToast } from "vue-toastification";

    const route = useRoute();
    const post = ref(null);
    const comments = ref([]);
    const pagination = ref({});
    const isLoading = ref(true);
    const error = ref(null);
    const loadingMoreComments = ref(false);
    const toast = useToast();

    const loadComments = async (page = 1) => {
        try {
            const response = await fetchPostComments(post.value.id, page);

            if (page === 1) {
                comments.value = response.data;
            } else {
                comments.value = [...comments.value, ...response.data];
            }
            pagination.value = response.paginate;

        } catch (err) {
            error.value = err.message;
        }
    };

    const loadMoreComments = async (page) => {
        loadingMoreComments.value = true;
        try {
            const response = await fetchPostComments(post.value.id, page);
            comments.value = [...comments.value, ...response.data];
            pagination.value = response.paginate;
        } catch (err) {
            console.error('Failed to load comments:', err);
        } finally {
            loadingMoreComments.value = false;
        }
    };

    onMounted(async () => {
        try {
            const slug = route.params.slug;
            post.value = await fetchPostDetails(slug);
            await loadComments(1);
        } catch (err) {
            error.value = err.message;
        } finally {
            isLoading.value = false;
        }
    });

    const handleAddComment = async (newComment) => {
        try {
            const createdComment = await addComment(post.value.id, newComment.content);
            toast.success(createdComment.message);
            if(!createdComment.data.data.is_accepted) {
                return
            }
            comments.value.unshift({
                id: Date.now().toString(),
                comment: newComment.content,
                created_at: new Date().toISOString(),
                user: {
                    full_name: "You",
                    avatar_url: "https://ui-avatars.com/api/?name=You&background=random"
                },
                ...createdComment.data.data
            });
        } catch (err) {
            console.error('Failed to add comment:', err);
        }
    };
</script>

<style scoped>
    .post-details {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>
