<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Upgrade Requests</h1>
        </div>

        <!-- Loading/Error States -->
        <div v-if="isLoading" class="text-center py-4">Loading...</div>
        <div v-if="error" class="text-center py-4 text-red-500">
            {{ error }}
        </div>

        <!-- Data Table -->
        <div v-if="!isLoading && !error">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            ID
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            User
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Aplied At
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="request in items" :key="request.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ request.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ request.user.full_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="{
                                    'text-yellow-600':
                                        request.status === 'pending',
                                    'text-green-600':
                                        request.status === 'approved',
                                    'text-red-600':
                                        request.status === 'rejected',
                                }"
                            >
                                {{ request.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ request.created_at }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <button
                                    v-if="request.status === 'pending'"
                                    @click="approveRequest(request.id)"
                                    class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200"
                                >
                                    Approve
                                </button>
                                <button
                                    v-if="request.status === 'pending'"
                                    @click="rejectRequest(request.id)"
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200"
                                >
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <PaginationControls
                :current-page="currentPage"
                :total-pages="totalPages"
                @next-page="nextPage"
                @prev-page="prevPage"
            />
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";
    import axios from "axios";
    import usePaginationFetcher from "../../composables/usePaginationFetcher";
    import PaginationControls from "../../views/components/PaginationControls.vue";
    import { useToast } from "vue-toastification";
    import Swal from 'sweetalert2';
    const toast = useToast();

    const {
        items,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher("upgrade-request");

    fetchData();

    const approveRequest = async (id) => {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!'
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            await axios.post(`/upgrade-request/${id}/accept`);
            toast.success("Request approved successfully!");
            fetchData();
        } catch (err) {
            console.log(err);
            toast.error(err.response?.data?.message || "Something went wrong.");
        }
    };

    const rejectRequest = async (id) => {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject it!'
        });

        if (!result.isConfirmed) {
            return;
        }
        try {
            await axios.post(`/upgrade-request/${id}/reject`);
            toast.success("Request rejected.");
            fetchData();
        } catch (err) {
            toast.error(err.response?.data?.message || "Something went wrong.");
        }
    };
</script>

<style scoped></style>
