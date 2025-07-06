<!-- views/dashboard/Categories.vue -->
<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
            <button
                @click="openCreateModal"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 cursor-pointer"
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in items" :key="item.id" :class="{'bg-gray-100': item.deleted_at}">
                        <td class="px-6 py-4 whitespace-nowrap">{{ item.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ item.title }}
                            <span v-if="item.deleted_at" class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                Deleted
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ item.slug }}</td>
                        <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <button
                            v-if="!item.deleted_at"
                            @click="openEditModal(item)"
                            class="p-2 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-500 transition-colors"
                            title="Edit"
                            >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            </button>

                            <!-- View Logs Button -->
                            <router-link
                            :to="{ name: 'category-logs', params: { id: item.id } }"
                            class="p-2 rounded-full bg-purple-50 hover:bg-purple-100 text-purple-500 transition-colors"
                            title="View Activity Logs"
                            >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            </router-link>

                            <!-- Delete Button -->
                            <button
                            v-if="!item.deleted_at"
                            @click="confirmDelete(item.id)"
                            class="p-2 rounded-full bg-red-50 hover:bg-red-100 text-red-500 transition-colors"
                            title="Delete"
                            >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            </button>

                            <!-- Restore Button -->
                            <button
                            v-if="item.deleted_at"
                            @click="restoreItem(item.id)"
                            class="p-2 rounded-full bg-green-50 hover:bg-green-100 text-green-500 transition-colors"
                            title="Restore"
                            >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            </button>

                            <!-- Permanent Delete Button -->
                            <button
                            v-if="item.deleted_at"
                            @click="confirmForceDelete(item.id)"
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

            <!-- Reusable Pagination -->
            <PaginationControls
                :current-page="currentPage"
                :total-pages="totalPages"
                @next-page="nextPage"
                @prev-page="prevPage"
            />
        </div>

        <!-- Create Category Modal -->
        <div
            v-if="isCreateModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Create Category</h2>
                <form @submit.prevent="createCategory">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newCategory.title"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.title }"
                        />
                        <ErrorMessage :errors="errors" field="title" />
                    </div>

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

        <!-- Edit Category Modal -->
        <div
            v-if="isEditModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Edit Category</h2>
                <form @submit.prevent="updateCategory">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newCategory.title"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.title }"
                        />
                        <ErrorMessage :errors="errors" field="title" />
                    </div>

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
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import usePaginationFetcher from "../../composables/usePaginationFetcher";
    import PaginationControls from "../../views/components/PaginationControls.vue";
    import { ref } from "vue";
    import axios from "axios";
    import { useToast } from "vue-toastification";
    import { useFormValidation } from "../../composables/useFormValidation";
    import ErrorMessage from "../../views/components/ErrorMessage.vue";
    import Swal from 'sweetalert2'

    const {
        items,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher("category");

    // Add validation setup
    const initialFormState = {
        title: "",
    };

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    // Fetch data on mount
    fetchData();

    const toast = useToast();
    const isCreateModalOpen = ref(false);
    const newCategory = ref({
        title: "",
    });

    const openCreateModal = () => {
        isCreateModalOpen.value = true;
        clearErrors();
    };

    const closeCreateModal = () => {
        isCreateModalOpen.value = false;
        newCategory.value = {
            title: "",
        };
        clearErrors();
    };

    const createCategory = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("title", newCategory.value.title);

            await axios.post("/category", formData);

            toast.success("Category created successfully!");
            closeCreateModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    const isEditModalOpen = ref(false);
    const editingCategoryId = ref(null);

    // Edit Category Functions
    const openEditModal = (category) => {
        editingCategoryId.value = category.id;
        newCategory.value = {
            title: category.title,
        };
        isEditModalOpen.value = true;
    };

    const updateCategory = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("title", newCategory.value.title);
            await axios.post(`/category/${editingCategoryId.value}`, formData);

            toast.success("Category updated successfully!");
            closeEditModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    // Delete Category Function
    const confirmDelete = async (id) => {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/category/${id}`);

                Swal.fire(
                    'Deleted!',
                    'Category has been deleted.',
                    'success'
                );

                fetchData(); // Refresh the data
            } catch (error) {
                console.log(error);
                Swal.fire(
                    'Error!',
                    error.response.data.message,
                    'error'
                );
                console.error(error);
            }
        }
    };

    const closeEditModal = () => {
        isEditModalOpen.value = false;
        editingCategoryId.value = null;
        newCategory.value = {
            title: "",
        };
        clearErrors();
    };

    const restoreItem = async (id) => {
        try {
            const result = await Swal.fire({
                title: 'Restore Category?',
                text: "Are you sure you want to restore this category?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restore it!'
            });

            if (result.isConfirmed) {
                await axios.post(`/category/${id}/restore`);
                toast.success("Category restored successfully!");
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
                await axios.delete(`/category/${id}/force`);
                toast.success("Category permanently deleted!");
                fetchData();
            } catch (error) {
                toast.error(error.response.data.message);
                console.error(error);
            }
        }
    };

</script>
