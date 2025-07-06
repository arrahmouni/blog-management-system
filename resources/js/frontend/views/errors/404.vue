<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
      <h1 class="text-9xl font-bold text-gray-800">404</h1>
      <p class="text-2xl text-gray-600 mt-4">Oops! Page not found.</p>
      <p class="text-lg text-gray-500 mt-2">
        The page you're looking for doesn't exist or has been moved.
      </p>
      <router-link
        to="/"
        class="mt-8 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
        Go Back Home
      </router-link>
    </div>
  </template>

<script setup>
  import { RouterLink } from 'vue-router';
</script>
