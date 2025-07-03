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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in products" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap">{{ product.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="w-16 h-16 object-cover rounded"
                            >
                            <div v-else class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No Image</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ product.price_with_currency }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="statusClasses(product.is_active)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ product.is_active ? "Active" : "Inactive" }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <!-- Edit Button -->
                            <button
                                @click="openEditModal(product)"
                                class="text-blue-500 hover:text-blue-700"
                                title="Edit"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button
                                @click="confirmDelete(product.id)"
                                class="text-red-500 hover:text-red-700"
                                title="Delete"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
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

        <!-- Create Product Modal -->
        <div
            v-if="isCreateModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3 max-h-[80vh] overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">Create Product</h2>
                <form @submit.prevent="createProduct">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newProduct.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.name }"
                            @input="generateSlug"
                        />
                        <ErrorMessage :errors="errors" field="name" />
                    </div>

                    <!-- Slug Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Slug</label>
                        <input
                            v-model="newProduct.slug"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.slug }"
                        />
                        <ErrorMessage :errors="errors" field="slug" />
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea
                            v-model="newProduct.description"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.description }"
                            rows="3"
                        ></textarea>
                        <ErrorMessage :errors="errors" field="description" />
                    </div>

                    <!-- Price Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Price</label>
                        <input
                            v-model="newProduct.price"
                            type="number"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.price }"
                            step="0.01"
                        />
                        <ErrorMessage :errors="errors" field="price" />
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
                                {{ category.name }}
                            </option>
                        </select>
                        <ErrorMessage :errors="errors" field="category_ids" />
                    </div>


                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="newProduct.is_active"
                                class="rounded text-blue-500"
                                :class="{ 'border-red-500': errors.is_active }"
                            />
                            <span class="text-gray-700">Active</span>
                            <ErrorMessage :errors="errors" field="is_active" />
                        </label>
                    </div>

                    <!-- Image Upload -->
                    <ImageUpload
                        v-model="newProduct.image"
                        :field-name="'product_image'"
                        :label="'Product Image'"
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

        <!-- Edit Product Modal -->
        <div
            v-if="isEditModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
        <div class="bg-white rounded-lg p-6 w-1/3 max-h-[80vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Edit Product</h2>
                <form @submit.prevent="updateProduct">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newProduct.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.name }"
                            @input="generateSlug"
                        />
                        <ErrorMessage :errors="errors" field="name" />
                    </div>

                    <!-- Slug Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Slug</label>
                        <input
                            v-model="newProduct.slug"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.slug }"
                        />
                        <ErrorMessage :errors="errors" field="slug" />
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea
                            v-model="newProduct.description"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.description }"
                            rows="3"
                        ></textarea>
                        <ErrorMessage :errors="errors" field="description" />
                    </div>

                    <!-- Price Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Price</label>
                        <input
                            v-model="newProduct.price"
                            type="number"
                            class="w-full px-3 py-2 border rounded-lg"
                            :class="{ 'border-red-500': errors.price }"
                            step="0.01"
                        />
                        <ErrorMessage :errors="errors" field="price" />
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
                                {{ category.name }}
                            </option>
                        </select>
                        <ErrorMessage :errors="errors" field="category_ids" />
                    </div>


                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="newProduct.is_active"
                                class="rounded text-blue-500"
                                :class="{ 'border-red-500': errors.is_active }"
                            />
                            <span class="text-gray-700">Active</span>
                            <ErrorMessage :errors="errors" field="is_active" />
                        </label>
                    </div>

                    <!-- Image Upload -->
                    <ImageUpload
                        v-model="newProduct.image"
                        :field-name="'product_image'"
                        :label="'Product Image'"
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
    </div>
</template>

<script setup>
    import usePaginationFetcher from "@/composables/usePaginationFetcher";
    import PaginationControls from "@/views/components/PaginationControls.vue";
    import { ref, onMounted } from "vue";
    import axios from "axios";
    import { useToast } from "vue-toastification";
    import ImageUpload from "@/views/components/Form/ImageUpload.vue";
    import { useFormValidation } from "@/composables/useFormValidation";
    import ErrorMessage from "@/views/components/ErrorMessage.vue";

    const categories = ref([]);
    const selectedCategories = ref([]);

    const initialFormState = {
        name: "",
        slug: "",
        price: 0,
        is_active: true,
        image: null,
        description: "",
        category_ids: [],
    }

    const { form, errors, clearErrors, handleApiError } = useFormValidation(initialFormState);

    const loadCategories = async () => {
        try {
            const response = await axios.get('/categories');
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
        items: products,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher("products");

    // Fetch products on mount
    fetchData();

    const toast = useToast();
    const isCreateModalOpen = ref(false);
    const newProduct = ref({
        name: "",
        slug: "",
        price: 0,
        is_active: true,
        image: null,
        description: "",
        category_ids: [],
    });

    // Auto-generate slug from name
    const generateSlug = () => {
        newProduct.value.slug = newProduct.value.name
            .toLowerCase()
            .replace(/ /g, "-")
            .replace(/[^\w-]+/g, "");
    };

    const openCreateModal = () => {
        isCreateModalOpen.value = true;
        selectedCategories.value = [];
        loadCategories();
        clearErrors();
    };

    const closeCreateModal = () => {
        isCreateModalOpen.value = false;
        newProduct.value = {
            name: "",
            slug: "",
            price: 0,
            is_active: true,
            image: null,
            description: "",
            category_ids: [],
        };
        clearErrors();
    };

    const createProduct = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("name", newProduct.value.name);
            formData.append("slug", newProduct.value.slug);
            formData.append("description", newProduct.value.description);
            formData.append("price", newProduct.value.price);
            formData.append("is_active", newProduct.value.is_active ? 1 : 0);
            if (newProduct.value.image) {
                formData.append("image", newProduct.value.image);
            }
            selectedCategories.value.forEach(categoryId => {
                formData.append("category_ids[]", categoryId);
            });

            await axios.post("/products", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Product created successfully!");
            closeCreateModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    // Edit Product
    const isEditModalOpen = ref(false);
    const editingProductId = ref(null);
    const existingImageUrl = ref(null);

    const openEditModal = (product) => {
        editingProductId.value = product.id;
        existingImageUrl.value = product.image;
        newProduct.value = {
            name: product.name,
            slug: product.slug,
            description: product.description,
            price: product.price,
            is_active: product.is_active,
            image: null,
            category_ids: product.categories.map((cat) => cat.id),
        };
        isEditModalOpen.value = true;
        selectedCategories.value = product.categories.map(c => c.id)
        loadCategories();
        clearErrors();
    };

    const updateProduct = async () => {
        try {
            clearErrors();
            const formData = new FormData();
            formData.append("name", newProduct.value.name);
            formData.append("slug", newProduct.value.slug);
            formData.append("description", newProduct.value.description);
            formData.append("price", newProduct.value.price);
            formData.append("is_active", newProduct.value.is_active ? 1 : 0);
            formData.append("_method", "PUT");

            if (newProduct.value.image) {
                formData.append("image", newProduct.value.image);
            }

            selectedCategories.value.forEach(categoryId => {
                formData.append("category_ids[]", categoryId);
            });

            await axios.post(`/products/${editingProductId.value}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Product updated successfully!");
            closeEditModal();
            fetchData();
        } catch (error) {
            handleApiError(error);
        }
    };

    // Delete Product
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
                await axios.delete(`/products/${id}`);

                Swal.fire("Deleted!", "Product has been deleted.", "success");

                fetchData(); // Refresh the data
            } catch (error) {
                Swal.fire("Error!", "Failed to delete product.", "error");
                console.error(error);
            }
        }
    };

    const closeEditModal = () => {
        isEditModalOpen.value = false;
        editingProductId.value = null;
        existingImageUrl.value = null;
        newProduct.value = {
            name: "",
            slug: "",
            price: 0,
            is_active: true,
            image: null,
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

    onMounted(() => {
        loadCategories(); // Load categories when the component mounts
    });
</script>
