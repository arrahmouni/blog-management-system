<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="mx-auto px-4">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo/Title -->
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-gray-800"></span>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative ml-3">
                        <button
                            @click="toggleDropdown"
                            class="flex items-center space-x-2 focus:outline-none">
                            <span class="text-gray-600">
                                {{ userEmail }}
                            </span>
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-show="isDropdownOpen"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                        >
                            <div class="py-1">
                                <button
                                    @click="handleLogout"
                                    class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="bg-white w-64 min-h-screen shadow-lg">
                <div class="p-4">
                    <!-- Logo -->
                    <div class="mb-8">
                        <router-link
                            :to="{ name: 'Home' }"
                            class="text-xl font-bold text-gray-800"
                        >
                            Dashboard
                        </router-link>
                    </div>

                    <!-- Navigation -->
                    <nav>
                        <div class="mb-6">
                            <p
                                class="text-sm font-semibold text-gray-500 mb-2"
                            >
                                Blog Management
                            </p>
                            <ul class="space-y-2">
                                <li v-if="isAdmin">
                                    <router-link
                                        :to="{ name: 'Categories' }"
                                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
                                        active-class="bg-gray-100"
                                    >
                                        Categories
                                    </router-link>
                                </li>
                                <!-- <li>
                                    <router-link
                                        :to="{ name: 'Products' }"
                                        class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded"
                                        active-class="bg-gray-100"
                                    >
                                        Products
                                    </router-link>
                                </li> -->
                            </ul>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted  } from "vue";
    import { useRouter } from "vue-router";
    import { useToast } from "vue-toastification";
    import axios from "axios";
    import { useUser } from "@/composables/useUser";
    const { isAdmin, isWriter } = useUser();

    const isDropdownOpen = ref(false);

    const toggleDropdown = () => {
        isDropdownOpen.value = !isDropdownOpen.value;
    };

    const router = useRouter();
    const toast = useToast();
    const userEmail = ref('');

    onMounted(async () => {
        try {
            const response = await axios.get('/user');
            userEmail.value = response.data.data.user.email;
        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    });

    const handleLogout = async () => {
        try {
            await axios.post("/logout");
            localStorage.removeItem("authToken");
            localStorage.removeItem("userRole");
            delete axios.defaults.headers.common["Authorization"];
            toast.success("Logged out successfully!");
            router.push("/admin/login");
        } catch (error) {
            toast.error("An error occurred during logout. Please try again.");
        }
    };
</script>
