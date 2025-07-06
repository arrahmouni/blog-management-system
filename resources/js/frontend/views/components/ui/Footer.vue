<template>
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">B</span>
                        </div>
                        <span class="ml-2 text-xl font-bold">Blogium</span>
                    </div>
                    <p class="mt-4 text-gray-400">
                        A modern blog platform where you can freely express your thoughts.
                    </p>
                    <div class="mt-6 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold">Links</h3>
                    <div class="mt-4 space-y-2 footer-links">
                        <a href="#" class="block text-gray-400 hover:text-white">Home</a>
                        <!-- <a href="#" class="block text-gray-400 hover:text-white">Blog</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Categories</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Popular Posts</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Authors</a> -->
                    </div>
                </div>

                <!-- <div>
                    <h3 class="text-lg font-semibold">Company</h3>
                    <div class="mt-4 space-y-2 footer-links">
                        <a href="#" class="block text-gray-400 hover:text-white">About Us</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Contact</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Careers</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Privacy Policy</a>
                        <a href="#" class="block text-gray-400 hover:text-white">Terms of Service</a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold">Newsletter</h3>
                    <p class="mt-4 text-gray-400">
                        Subscribe to stay updated with our latest blog posts.
                    </p>
                    <form class="mt-4 flex">
                        <input type="email" placeholder="Your email address" class="px-4 py-2 w-full rounded-l-lg focus:outline-none text-gray-800">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-lg hover:bg-indigo-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div> -->
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-gray-400">
                    &copy; {{ new Date().getFullYear() }} Blogium. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>

<script setup>
</script>
