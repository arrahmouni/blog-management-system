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
                        <a href="#" class="text-indigo-600 border-b-2 border-indigo-500 px-1 pt-1 font-medium">Home</a>
                        <!-- <a href="#" class="text-gray-500 hover:text-gray-700 px-1 pt-1 font-medium">Posts</a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 px-1 pt-1 font-medium">Categories</a> -->
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="hidden md:ml-4 md:flex md:flex-shrink-0 md:items-center">
                        <button class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-indigo-600 hover:bg-indigo-50">Sign In</button>
                        <button class="ml-4 px-4 py-2 rounded-md bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700">Sign Up</button>
                    </div>
                    <button class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
  </nav>
</template>

<script setup>

</script>
