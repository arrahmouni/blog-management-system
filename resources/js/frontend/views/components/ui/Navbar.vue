<template>
  <nav class="navbar fixed w-full z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">B</span>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">Blogium</span>
                    </div>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <router-link
                            to="/"
                            class="text-indigo-600 border-b-2 border-indigo-500 px-1 pt-1 font-medium"
                        >
                        Home
                        </router-link>
                        <router-link
                            to="/posts"
                            class="text-gray-500 hover:text-gray-700 px-1 pt-1 font-medium"
                        >
                            Posts
                        </router-link>
                        <!--
                        <a href="#" class="text-gray-500 hover:text-gray-700 px-1 pt-1 font-medium">Categories</a> -->
                    </div>
                </div>
        <div class="flex items-center">
          <template v-if="isAuthenticated">
            <button
              @click="handleLogout"
              class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700"
            >
              Logout
            </button>
          </template>
          <template v-else>
            <div class="hidden md:ml-4 md:flex md:flex-shrink-0 md:items-center">
              <router-link
                to="/login"
                class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-indigo-600 hover:bg-indigo-50"
              >
                Sign In
              </router-link>
              <router-link
                to="/register"
                class="ml-4 px-4 py-2 rounded-md bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700"
              >
                Sign Up
              </router-link>
            </div>
          </template>
        </div>

            </div>
        </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from "vue-toastification";
import { useUser } from '../../../../admin/composables/useUser';

const toast = useToast();
const router = useRouter();
const { isAuthenticated, logout } = useUser();

const handleLogout = async () => {
  try {
    await axios.post("/logout");
    logout();
    delete axios.defaults.headers.common["Authorization"];
    toast.success("Logged out successfully!");
    router.push("/");
  } catch (error) {
    toast.error("An error occurred during logout.");
  }
};
</script>
