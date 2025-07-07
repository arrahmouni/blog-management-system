<template>
    <div class="hero-gradient pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="md:w-1/2 text-center md:text-left">
                    <h1
                        class="text-4xl md:text-5xl font-extrabold text-white leading-tight"
                    >
                        Share Your Thoughts, <br />Change the World
                    </h1>
                    <p class="mt-4 text-xl text-indigo-100 max-w-xl">
                        Create, edit, and share your posts with thousands of
                        readers on Blogium.
                    </p>
                    <div
                        class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4"
                    >
                    <div
                        v-if="isAuthenticated && isUser && hasPendingRequest"
                        class="px-6 py-3 bg-gradient-to-r from-yellow-500/20 to-yellow-600/30 border border-yellow-500/30 text-yellow-100 font-medium rounded-lg inline-flex items-center shadow-lg"
                    >
                        <i class="fas fa-hourglass-half mr-2 text-yellow-300 animate-pulse"></i>
                        <span class="text-shadow">
                            Your upgrade request is pending approval
                        </span>
                    </div>

                        <!-- Apply Button -->
                        <button
                            v-else-if="isAuthenticated && isUser"
                            @click="applyForWriter"
                            class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition duration-300 shadow-lg"
                        >
                            Become a Writer – Apply Now
                            <i class="fas fa-pen-nib ml-2"></i>
                        </button>

                    </div>
                </div>
                <div class="mt-12 md:mt-0 md:w-1/2 flex justify-center">
                    <div class="relative">
                        <div
                            class="w-64 h-64 md:w-80 md:h-80 bg-white rounded-2xl shadow-2xl transform rotate-6 overflow-hidden"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-600 opacity-80"
                            ></div>
                        </div>
                        <div
                            class="absolute top-0 w-64 h-64 md:w-80 md:h-80 bg-white rounded-2xl shadow-2xl transform -rotate-3 overflow-hidden"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-700 opacity-90"
                            ></div>
                        </div>
                        <div
                            class="absolute top-0 w-64 h-64 md:w-80 md:h-80 bg-white rounded-2xl shadow-2xl overflow-hidden flex items-center justify-center"
                        >
                            <div class="text-center p-6">
                                <i
                                    class="fas fa-pen-nib text-5xl text-indigo-600 mb-4"
                                ></i>
                                <h3 class="text-2xl font-bold text-gray-800">
                                    Modern Blog Platform
                                </h3>
                                <p class="text-gray-600 mt-2">
                                    Designed specifically for writers
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch } from "vue";
    import Swal from "sweetalert2";
    import { useToast } from "vue-toastification";
    import { useUser } from "../../../../admin/composables/useUser";
    import axios from "axios";
    const { isAuthenticated, isUser } = useUser();
    const toast = useToast();
    const hasPendingRequest = ref(false);

    onMounted(async () => {
        if (isAuthenticated.value) {
            await checkUpgradeRequestStatus();
        }
    });

    watch(isAuthenticated, async (newVal) => {
        if (newVal) {
            await checkUpgradeRequestStatus();
        } else {
            hasPendingRequest.value = false;
        }
    });

    const checkUpgradeRequestStatus = async () => {
        try {
            const response = await axios.get('/upgrade-request/get/status');
            hasPendingRequest.value = response.data.data.has_pending;
        } catch (error) {
            console.error('Error checking upgrade status:', error);
            hasPendingRequest.value = false;
        }
    };

    const applyForWriter = async () => {
        try {
            const result = await Swal.fire({
                title: "Become a Writer?",
                text: "Are you sure you want to apply for writer privileges?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, apply now!",
                cancelButtonText: "Cancel",
                reverseButtons: true,
                backdrop: "rgba(0,0,0,0.7)",
            });

            if (result.isConfirmed) {
                Swal.fire({
                    title: "Processing Application...",
                    text: "Please wait while we submit your request",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                const response = await axios.post('/upgrade-request/apply');

                Swal.close();

                toast.success(response.data.data.message || 'Application submitted successfully!');

                hasPendingRequest.value = true;
            }
        } catch (error) {
            Swal.close();
            toast.error(
                error.message || "Failed to submit application. Please try again.");
        }
    };
</script>
