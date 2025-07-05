<!-- views/dashboard/Posts.vue -->
<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Posts</h1>
            <button
                @click="openCreateModal"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
            >
                Add New
            </button>
        </div>

        <!-- Loading/Error States -->
        <div v-if="isLoading" class="text-center py-4">Loading...</div>
        <div v-if="error" class="text-center py-4 text-red-500">
            {{ error }}
        </div>

        <!-- Data Table -->
        <div v-if="!isLoading && !error">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Table Headers -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="post in posts" :key="post.id" :class="{'bg-gray-100': post.deleted_at}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ post.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                v-if="post.image_url"
                                :src="post.image_url"
                                :alt="post.title"
                                class="w-16 h-16 object-cover rounded"
                            >
                            <div v-else class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No Image</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ post.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ post.slug }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="statusClasses(post.is_published && !post.deleted_at)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ post.is_published && !post.deleted_at ? "Published" : "Draft" }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ post.published_at }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-2">
                                <!-- View Details Button -->
                                <button
                                    @click="openViewModal(post)"
                                    class="p-2 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-500 transition-colors"
                                    title="View Details"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- View Logs Button -->
                                <router-link
                                    :to="{ name: 'post-logs', params: { id: post.id } }"
                                    class="p-2 rounded-full bg-purple-50 hover:bg-purple-100 text-purple-500 transition-colors"
                                    title="View Activity Logs"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </router-link>

                                <!-- Edit Button -->
                                <button
                                    v-if="!post.deleted_at"
                                    @click="openEditModal(post)"
                                    class="p-2 rounded-full bg-green-50 hover:bg-green-100 text-green-500 transition-colors"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button
                                    v-if="!post.deleted_at"
                                    @click="confirmDelete(post.id)"
                                    class="p-2 rounded-full bg-red-50 hover:bg-red-100 text-red-500 transition-colors"
                                    title="Delete"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                <!-- Restore Button -->
                                <button
                                    v-if="post.deleted_at && isAdmin"
                                    @click="restoreItem(post.id)"
                                    class="p-2 rounded-full bg-green-50 hover:bg-green-100 text-green-500 transition-colors"
                                    title="Restore"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>

                                <!-- Permanent Delete Button -->
                                <button
                                    v-if="post.deleted_at && isAdmin"
                                    @click="confirmForceDelete(post.id)"
                                    class="p-2 rounded-full bg-red-100 hover:bg-red-200 text-red-700 transition-colors"
                                    title="Delete Permanently"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <PaginationControls
                :current-page="currentPage"
                :total-pages="totalPages"
                @next-page="nextPage"
                @prev-page="prevPage"
            />
        </div>

        <!-- Create Post Modal -->
        <div
            v-if="isCreateModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3 max-h-[80vh] overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">Create Post</h2>
                <form @submit.prevent="createPost">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newPost.title"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.title }"
                        />
                        <ErrorMessage :errors="errors" field="title" />
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Content</label>
                        <textarea
                            v-model="newPost.body"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.body }"
                            rows="3"
                        ></textarea>
                        <ErrorMessage :errors="errors" field="body" />
                    </div>

                    <!-- Categories (Multi-Select) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Categories</label>
                        <select
                            v-model="selectedCategories"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.category_ids }"
                            multiple
                        >
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.title }}
                            </option>
                        </select>
                        <ErrorMessage :errors="errors" field="category_ids" />
                    </div>

                    <!-- Image Upload -->
                    <ImageUpload
                        v-model="newPost.image"
                        :field-name="'post_image'"
                        :label="'Post Image'"
                        :accepted-extensions="'image/jpeg, image/png'"
                        :max-size="10"
                        @file-selected="handleFileSelected"
                        @file-removed="handleFileRemoved"
                    />
                    <ErrorMessage :errors="errors" field="image" />

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="closeCreateModal"
                            class="mr-2 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Post Modal -->
        <div
            v-if="isEditModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
        <div class="bg-white rounded-lg p-6 w-1/3 max-h-[80vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Edit Post</h2>
                <form @submit.prevent="updatePost">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newPost.title"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.title }"
                        />
                        <ErrorMessage :errors="errors" field="title" />
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Content</label>
                        <textarea
                            v-model="newPost.body"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.body }"
                            rows="3"
                        ></textarea>
                        <ErrorMessage :errors="errors" field="body" />
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Categories</label>
                        <select
                            v-model="selectedCategories"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.category_ids }"
                            multiple
                        >
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.title }}
                            </option>
                        </select>
                        <ErrorMessage :errors="errors" field="category_ids" />
                    </div>

                    <!-- Image Upload -->
                    <ImageUpload
                        v-model="newPost.image"
                        :field-name="'post_image'"
                        :label="'Post Image'"
                        :accepted-extensions="'image/jpeg, image/png'"
                        :max-size="10"
                        :existing-image="existingImageUrl"
                        @file-selected="handleFileSelected"
                        @file-removed="handleFileRemoved"
                    />
                    <ErrorMessage :errors="errors" field="image" />

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="closeEditModal"
                            class="mr-2 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Post Modal -->
        <div v-if="isViewModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" @click="closeIfBackdrop" ref="modalBackdrop">
            <div class="bg-white rounded-lg p-6 w-1/2 max-h-screen overflow-y-auto" ref="modalContent" @scroll="handleScroll">
                <div v-if="loadingPostDetails" class="text-center py-8">
                    <p>Loading post details...</p>
                </div>
                <div v-else>
                    <h2 class="text-xl font-bold mb-4">{{ currentPost.title }}</h2>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="flex items-center">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                            <div class="ml-3">
                                <p class="font-semibold">Author</p>
                                <p>{{ currentPost.user?.full_name || 'Unknown' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="font-semibold">Status</p>
                            <span :class="statusClasses(currentPost.is_published)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ currentPost.is_published ? "Published" : "Draft" }}
                            </span>
                        </div>

                        <div v-if="currentPost.published_at">
                            <p class="font-semibold">Published At</p>
                            <p>{{ currentPost.published_at }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="font-semibold mb-2">Featured Image</p>
                        <img
                            v-if="currentPost.image_url"
                            :src="currentPost.image_url"
                            alt="Post image"
                            class="max-w-full h-auto max-h-64 object-contain"
                        >
                        <div v-else class="bg-gray-100 p-4 text-center rounded-lg">
                            <p class="text-gray-500">No featured image</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="font-semibold mb-2">Categories</p>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="category in currentPost.categories"
                                :key="category.id"
                                class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
                            >
                                {{ category.title }}
                            </span>
                            <span v-if="!currentPost.categories?.length" class="text-gray-500">
                                No categories assigned
                            </span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="font-semibold mb-2">Content</p>
                        <div class="prose max-w-none p-4 bg-gray-50 rounded-lg" v-html="currentPost.body"></div>
                    </div>

                    <div class="mt-8 mb-8 bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Comments ({{ totalComments || 0 }})
                        </h3>

                        <div v-if="loadingComments" class="text-center py-4">
                            Loading comments...
                        </div>

                        <div v-else-if="!comments.length" class="text-center py-4 text-gray-500">
                            No comments yet.
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="comment in comments" :key="comment.id" class="border-b pb-4 last:border-0">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ comment.user.full_name }}</h4>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="text-sm text-gray-600">{{ comment.user.email }}</span>
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full">
                                                        {{ comment.user.role }}
                                                    </span>
                                                </div>
                                            </div>

                                            <span class="text-xs text-gray-500">
                                                {{ comment.created_at }}
                                            </span>
                                        </div>

                                        <p class="mt-2 text-gray-700">
                                            {{ comment.comment }}
                                        </p>

                                        <div class="mt-2 flex items-center">
                                            <span :class="comment.is_accepted ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                                class="px-2 py-1 text-xs font-medium rounded-full">
                                                {{ comment.is_accepted ? 'Accepted' : 'Pending' }}
                                            </span>

                                            <span class="ml-2 text-xs text-gray-500">
                                                {{ comment.user.phone }}
                                            </span>

                                            <div v-if="isAdmin" class="ml-auto flex space-x-2">
                                                <button
                                                    v-if="!comment.is_accepted && !comment.deleted_at"
                                                    @click="acceptComment(comment)"
                                                    :disabled="comment.loading"
                                                    class="text-xs px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                                >
                                                    <span v-if="comment.loading">Processing...</span>
                                                    <span v-else>Accept</span>
                                                </button>

                                                <button
                                                    v-if="!comment.deleted_at"
                                                    @click="deleteComment(comment)"
                                                    :disabled="comment.loading_delete"
                                                    class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                                >
                                                    <span v-if="comment.loading_delete">Processing...</span>
                                                    <span v-else>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="loadingMoreComments" class="text-center py-4">
                            Loading more comments...
                        </div>

                        <button
                            v-if="hasMoreComments && !loadingMoreComments && !autoLoadMore"
                            @click="loadMoreComments"
                            class="mt-4 w-full py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700"
                        >
                            Load More Comments
                        </button>
                    </div>

                    <div v-if="isAdmin" class="flex justify-end space-x-2">
                        <button
                            v-if="!currentPost.is_published && !currentPost.deleted_at"
                            @click="approvePost(currentPost)"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                        >
                            Approve & Publish
                        </button>

                        <button
                            @click="closeViewModal"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
                        >
                            Close
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script setup>
    import usePaginationFetcher from "@/composables/usePaginationFetcher";
    import PaginationControls from "@/views/components/PaginationControls.vue";
    import { ref, onMounted, onUnmounted  } from "vue";
    import axios from "axios";
    import { useToast } from "vue-toastification";
    import ImageUpload from "@/views/components/Form/ImageUpload.vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import ErrorMessage from "@/views/components/ErrorMessage.vue";
    import { useUser } from '@/composables/useUser'
    import Swal from 'sweetalert2'

    const { isAdmin } = useUser();
    const categories = ref([]);
    const selectedCategories = ref([]);
    const isViewModalOpen = ref(false);
    const currentPost = ref(null);
    const comments = ref([]);
    const totalComments = ref(0);
    const currentCommentsPage = ref(1);
    const commentsPerPage = 10;
    const hasMoreComments = ref(false);
    const loadingComments = ref(false);
    const loadingMoreComments = ref(false);
    const autoLoadMore = ref(true);
    const loadingPostDetails = ref(false);
    const modalBackdrop = ref(null);
    const modalContent = ref(null);

    const openViewModal = async (post) => {
        try {
            loadingPostDetails.value = true;
            const response = await axios.get(`/post/${post.id}`, {
                params: {
                    include: 'user,categories'
                }
            });

            currentPost.value = response.data.data.data;
            isViewModalOpen.value = true;

            await fetchComments();
        } catch (error) {
            toast.error(error.response.data.message);
        } finally {
            loadingPostDetails.value = false;
        }
    };

    const loadMoreComments = async () => {
        if (hasMoreComments.value && !loadingMoreComments.value) {
            await fetchComments(currentCommentsPage.value + 1);
        }
    };

    const handleScroll = (event) => {
        if (!autoLoadMore.value) return;

        const container = event.target;
        const scrollBottom = container.scrollHeight - container.scrollTop - container.clientHeight;

        if (scrollBottom < 200 && hasMoreComments.value && !loadingMoreComments.value) {
            loadMoreComments();
        }
    };

    const fetchComments = async (page = 1) => {
        try {
            if (page === 1) {
                comments.value = [];
                loadingComments.value = true;
            } else {
                loadingMoreComments.value = true;
            }

            const response = await axios.get(`/post/${currentPost.value.id}/comment`, {
                params: {
                    page: page,
                    per_page: commentsPerPage
                }
            });

            const data = response.data.data;
            comments.value = [...comments.value, ...data.data];
            totalComments.value = data.paginate.total;
            currentCommentsPage.value = page;
            hasMoreComments.value = data.paginate.current_page < data.paginate.last_page;
        } catch (error) {
            toast.error(error.response.data.message);
        } finally {
            loadingComments.value = false;
            loadingMoreComments.value = false;
        }
    };

    const closeViewModal = () => {
        isViewModalOpen.value = false;
        currentPost.value = null;
        comments.value = [];
        currentCommentsPage.value = 1;
        totalComments.value = 0;
    };

    const initialFormState = {
        title: "",
        image: null,
        body: "",
        category_ids: [],
    }

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const approvePost = async (post) => {
        try {
            await axios.put(`/post/${post.id}/approve`);
            toast.success("Post approved and published!");
            await fetchData();
            closeViewModal();
        } catch (error) {
            toast.error(error.response.data.message);
        }
    };

    const acceptComment = async (comment) => {
        try {
            comment.loading = true;
            await axios.put(`/comment/${comment.id}/accept`);
            comment.is_accepted = true;
            toast.success("Comment accepted");
        } catch (error) {
            toast.error(error.response.data.message);
            console.error("Comment acceptance error:", error.response?.data || error.message);
        } finally {
            comment.loading = false;
        }
    };

    const deleteComment = async (comment) => {
        try {
            comment.loading_delete = true;
            await axios.delete(`/comment/${comment.id}`);
            toast.success("Comment deleted successfully");
            comments.value = comments.value.filter((c) => c.id !== comment.id);
        } catch (error) {
            toast.error(error.response.data.message);
            console.error("Comment deletion error:", error.response?.data || error.message);
        } finally {
            comment.loading_delete = false;
        }
    };

    const restoreItem = async (id) => {
        try {
            const result = await Swal.fire({
                title: 'Restore Post?',
                text: "Are you sure you want to restore this post?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            });

            if (result.isConfirmed) {
                await axios.post(`/post/${id}/restore`);
                toast.success("Post restored successfully!");
                fetchData();
            }
        } catch (error) {
            toast.error(error.response.data.message);
            console.error(error);
        }
    };

    const confirmForceDelete = async (id) => {
        const result = await Swal.fire({
            title: 'Delete Permanently?',
            text: "This cannot be undone! All related data will be lost.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete Permanently'
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/post/${id}/force`);
                toast.success("Post permanently deleted!");
                fetchData();
            } catch (error) {
                toast.error(error.response.data.message);
                console.error(error);
            }
        }
    };

    const loadCategories = async () => {
        try {
            const response = await axios.get('/category');
            categories.value = response.data.data.data;
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    };

    const statusClasses = (status) => ({
        "bg-green-100 text-green-800": status === true,
        "bg-red-100 text-red-800": status === false,
    });

    const {
        items: posts,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher("post");

    // Fetch posts on mount
    fetchData();

    const toast = useToast();
    const isCreateModalOpen = ref(false);
    const newPost = ref({
        title: "",
        image: null,
        body: "",
        category_ids: [],
    });

    const openCreateModal = () => {
        isCreateModalOpen.value = true;
        selectedCategories.value = [];
        loadCategories();
        clearErrors();
    };

    const closeCreateModal = () => {
        isCreateModalOpen.value = false;
        newPost.value = {
            title: "",
            image: null,
            body: "",
            category_ids: [],
        };
        clearErrors();
    };

    const createPost = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("title", newPost.value.title);
            formData.append("body", newPost.value.body);
            if (newPost.value.image) {
                formData.append("image", newPost.value.image);
            }
            selectedCategories.value.forEach(categoryId => {
                formData.append("category_ids[]", categoryId);
            });

            await axios.post("/post", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Post created successfully!");
            closeCreateModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    // Edit Post
    const isEditModalOpen = ref(false);
    const editingPostId = ref(null);
    const existingImageUrl = ref(null);

    const openEditModal = (post) => {
        editingPostId.value = post.id;
        existingImageUrl.value = post.image_url;
        newPost.value = {
            title: post.title,
            body: post.body,
            image: null,
            category_ids: post.categories.map((cat) => cat.id),
        };
        isEditModalOpen.value = true;
        selectedCategories.value = post.categories.map(c => c.id)
        loadCategories();
        clearErrors();
    };

    const updatePost = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("title", newPost.value.title);
            formData.append("body", newPost.value.body);

            if (newPost.value.image) {
                formData.append("image", newPost.value.image);
            }

            selectedCategories.value.forEach(categoryId => {
                formData.append("category_ids[]", categoryId);
            });

            await axios.post(`/post/${editingPostId.value}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Post updated successfully!");
            closeEditModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    // Delete Post
    const confirmDelete = async (id) => {
        const result = await Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/post/${id}`);

                Swal.fire("Deleted!", "Post has been deleted.", "success");

                fetchData();
            } catch (error) {
                Swal.fire("Error!", error.response.data.message, "error");
                console.error(error);
            }
        }
    };

    const closeEditModal = () => {
        isEditModalOpen.value = false;
        editingPostId.value = null;
        existingImageUrl.value = null;
        newPost.value = {
            title: "",
            body: "",
            image: null,
            published_at: null,
            category_ids: [],
        };
        clearErrors();
    };

    // File Handling
    const handleFileSelected = ({ file, fieldName }) => {
        console.log("File selected for field:", fieldName, file);
    };

    const handleFileRemoved = (fieldName) => {
        console.log("File removed from field:", fieldName);
    };

    const closeIfBackdrop = (event) => {
        if (event.target === modalBackdrop.value) {
            closeViewModal();
        }
    };

    const handleKeydown = (event) => {
        if (event.key === "Escape" && isViewModalOpen.value) {
            closeViewModal();
        }
    };

    onMounted(() => {
        loadCategories(); // Load categories when the component mounts
        window.addEventListener("keydown", handleKeydown);
    });

    onUnmounted(() => {
        window.removeEventListener("keydown", handleKeydown);
    });
</script>
