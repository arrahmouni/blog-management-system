<template>
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Featured Authors
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Discover content from our expert writers
                </p>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Author 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden relative">
                    <div class="author-badge bg-indigo-600 text-white px-4 py-2 rounded-full font-bold">
                        <i class="fas fa-crown mr-2"></i>Top Writer
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <img class="h-24 w-24 rounded-full border-4 border-white shadow-lg" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            <h3 class="mt-4 text-xl font-bold text-gray-900">James Wilson</h3>
                            <p class="text-indigo-600">Tech Writer</p>
                            <div class="mt-4 flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-linkedin text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-medium text-lg"></i>
                                </a>
                            </div>
                            <p class="mt-4 text-gray-600 text-center">
                                Over 10 years of tech experience sharing industry insights.
                            </p>
                            <div class="mt-6 w-full">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Posts</span>
                                    <span class="text-sm font-bold text-gray-900">142</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Followers</span>
                                    <span class="text-sm font-bold text-gray-900">24.8K</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Likes</span>
                                    <span class="text-sm font-bold text-gray-900">8.4K</span>
                                </div>
                            </div>
                            <button class="mt-6 w-full py-2 bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200">
                                Follow
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Author 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden relative">
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <img class="h-24 w-24 rounded-full border-4 border-white shadow-lg" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            <h3 class="mt-4 text-xl font-bold text-gray-900">Sophia Martinez</h3>
                            <p class="text-indigo-600">Health & Nutrition</p>
                            <div class="mt-4 flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-instagram text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-linkedin text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-youtube text-lg"></i>
                                </a>
                            </div>
                            <p class="mt-4 text-gray-600 text-center">
                                Nutritionist and dietitian sharing practical healthy living tips.
                            </p>
                            <div class="mt-6 w-full">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Posts</span>
                                    <span class="text-sm font-bold text-gray-900">87</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Followers</span>
                                    <span class="text-sm font-bold text-gray-900">18.2K</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Likes</span>
                                    <span class="text-sm font-bold text-gray-900">5.7K</span>
                                </div>
                            </div>
                            <button class="mt-6 w-full py-2 bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200">
                                Follow
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Author 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden relative">
                    <div class="author-badge bg-amber-500 text-white px-4 py-2 rounded-full font-bold">
                        <i class="fas fa-star mr-2"></i>Rising Star
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <img class="h-24 w-24 rounded-full border-4 border-white shadow-lg" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            <h3 class="mt-4 text-xl font-bold text-gray-900">David Chen</h3>
                            <p class="text-indigo-600">Travel Writer</p>
                            <div class="mt-4 flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-instagram text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-indigo-600">
                                    <i class="fab fa-pinterest text-lg"></i>
                                </a>
                            </div>
                            <p class="mt-4 text-gray-600 text-center">
                                Professional traveler creating guides based on 50+ countries experience.
                            </p>
                            <div class="mt-6 w-full">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Posts</span>
                                    <span class="text-sm font-bold text-gray-900">42</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-600">Followers</span>
                                    <span class="text-sm font-bold text-gray-900">12.5K</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Likes</span>
                                    <span class="text-sm font-bold text-gray-900">3.9K</span>
                                </div>
                            </div>
                            <button class="mt-6 w-full py-2 bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200">
                                Follow
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
