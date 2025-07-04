<!-- views/dashboard/CategoryLogs.vue -->
<template>
  <div class="bg-white rounded-lg shadow-sm p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Activity Logs for Category #{{ categoryId }}</h1>
      <button
        @click="goBack"
        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 cursor-pointer"
      >
        Back to Categories
      </button>
    </div>

    <!-- Loading/Error States -->
    <div v-if="isLoading" class="text-center py-4">Loading activity logs...</div>
    <div v-if="error" class="text-center py-4 text-red-500">
      {{ error }}
    </div>

    <!-- Logs Data Table -->
    <div v-if="!isLoading && !error">
      <table class="min-w-full divide-y divide-gray-200">
        <!-- Table Headers -->
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Causer</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>

        <!-- Table Body -->
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="log in logs" :key="log.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ log.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="eventBadgeClass(log.event)">{{ log.event }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ log.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ log.causer ? log.causer.name : 'System' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(log.created_at) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button
                v-if="hasChanges(log)"
                @click="openChangesModal(log)"
                class="text-blue-500 hover:text-blue-700"
                title="View Changes"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            <span v-else class="text-gray-500 text-sm">No attribute changes</span>

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

    <!-- Changes Modal -->
      <div
        v-if="isModalOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg p-6 w-1/2 max-h-screen overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Changes for Log #{{ selectedLog.id }}</h2>

        <div class="space-y-4">
            <template v-if="Object.keys(changes).length > 0">
            <div v-for="(change, field) in changes" :key="field" class="border-b pb-4">
                <h3 class="font-semibold text-lg capitalize">{{ field.replace(/_/g, ' ') }}</h3>
                <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Old Value</h4>
                    <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ change.old }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">New Value</h4>
                    <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ change.new }}</p>
                </div>
                </div>
            </div>
            </template>
            <div v-else class="text-center py-4 text-gray-500">
            No changes recorded for this event
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button
            @click="closeModal"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600"
            >
            Close
            </button>
        </div>
        </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, computed, onMounted } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import usePaginationFetcher from "@/composables/usePaginationFetcher";
    import PaginationControls from "@/views/components/PaginationControls.vue";
    import axios from "axios";
    import { useToast } from "vue-toastification";

    const route = useRoute();
    const router = useRouter();
    const toast = useToast();

    const categoryId = route.params.id;

    // Fetch activity logs
    const {
        items: logs,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher(`category/${categoryId}/logs`);

    onMounted(() => {
        fetchData();
    });

    const goBack = () => {
        router.push({ name: 'Categories' });
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleString();
    };

    const eventBadgeClass = (event) => {
        const classes = {
            'created': 'bg-green-100 text-green-800',
            'updated': 'bg-blue-100 text-blue-800',
            'deleted': 'bg-red-100 text-red-800',
            'restored': 'bg-yellow-100 text-yellow-800',
        };
        return `px-2 py-1 rounded-full text-xs ${classes[event] || 'bg-gray-100 text-gray-800'}`;
    };

    // Modal functionality
    const isModalOpen = ref(false);
    const selectedLog = ref(null);
    const changes = computed(() => {
        if (!selectedLog.value) return {};
        return selectedLog.value.changes || {};
    });

    const openChangesModal = (log) => {
        selectedLog.value = log;
        isModalOpen.value = true;
    };

    const closeModal = () => {
        isModalOpen.value = false;
        selectedLog.value = null;
    };

    const hasChanges = (log) => {
        const noChangeEvents = ['deleted', 'restored'];
        return !noChangeEvents.includes(log.event) && Object.keys(log.changes || {}).length > 0;
    };
</script>
