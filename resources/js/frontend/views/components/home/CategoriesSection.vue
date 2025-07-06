<template>
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Popular Categories
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Discover content based on your interests
                </p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="category-item bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <i class="fas fa-laptop-code text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Technology</h3>
                    <p class="mt-2 text-gray-600">Latest tech news and reviews</p>
                    <div class="mt-4 text-indigo-600 font-medium">
                        124 Posts
                    </div>
                </div>

                <div class="category-item bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                        <i class="fas fa-heartbeat text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Health</h3>
                    <p class="mt-2 text-gray-600">Healthy living and nutrition tips</p>
                    <div class="mt-4 text-indigo-600 font-medium">
                        87 Posts
                    </div>
                </div>

                <div class="category-item bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mb-4">
                        <i class="fas fa-plane text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Travel</h3>
                    <p class="mt-2 text-gray-600">Travel guides to explore the world</p>
                    <div class="mt-4 text-indigo-600 font-medium">
                        95 Posts
                    </div>
                </div>

                <div class="category-item bg-gray-50 rounded-xl p-6 shadow-sm border border-gray-100 cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Economy</h3>
                    <p class="mt-2 text-gray-600">Financial news and market analysis</p>
                    <div class="mt-4 text-indigo-600 font-medium">
                        68 Posts
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
