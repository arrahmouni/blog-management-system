<template>
    <div class="bg-white rounded-xl shadow-md overflow-hidden relative">
        <div class="p-6">
            <div class="flex flex-col items-center">
                <img
                    class="h-24 w-24 rounded-full border-4 border-white shadow-lg object-cover"
                    :src="getRandomAvatar()"
                    :alt="author.full_name"
                />
                <h3 class="mt-4 text-xl font-bold text-gray-900">{{ author.full_name }}</h3>
                <p class="text-indigo-600">Tech Writer</p>

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
                    Professional traveler creating guides based on 50+ countries experience
                </p>

                <div class="mt-6 w-full">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Posts</span>
                        <span class="text-sm font-bold text-gray-900">{{ author.posts_count }}</span>
                    </div>
                </div>

                <!-- <button class="mt-6 w-full py-2 bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200">
                    Follow
                </button> -->
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        author: {
            type: Object,
            required: true,
        },
    });

    const avatars = [
        "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
    ];

    const getRandomAvatar = () => {
        const index = Math.floor(Math.random() * avatars.length);
        return avatars[index];
    };

</script>
