<template>
    <div class="fixed bottom-4 right-4 z-50">
        <div
            v-for="notification in notifications"
            :key="notification.id"
            class="bg-white shadow-lg rounded-lg p-4 mb-3 w-80 transition-all duration-300"
            :class="{ 'animate-pulse': notification.isNew }"
        >
            <div class="flex items-start">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <svg
                        class="w-5 h-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">New Comment</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ notification.message }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        {{ notification.author }} - {{ notification.time }}
                    </p>
                </div>
                <button
                    @click="removeNotification(notification.id)"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
            <div class="mt-3 flex justify-end">
                <a
                    :href="'/posts/' + notification.post_id"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                >
                    View Post
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue";

    const notifications = ref([]);

    onMounted(() => {
        const userId = document.head.querySelector('meta[name="user-id"]')?.content;
        if (!userId) return;
        window.Echo.private(`user.${userId}`).notification((notification) => {
            addNotification({
                id: Date.now(),
                post_id: notification.post_id,
                post_title: notification.post_title,
                message: notification.message,
                author: notification.comment_author,
                time: "Just now",
                isNew: true,
            });
        });
    });

    const addNotification = (notification) => {
        notifications.value.unshift(notification);

        setTimeout(() => {
            const index = notifications.value.findIndex(
                (n) => n.id === notification.id
            );
            if (index !== -1) notifications.value[index].isNew = false;
        }, 3000);

        setTimeout(() => {
            removeNotification(notification.id);
        }, 10000);
    };

    const removeNotification = (id) => {
        notifications.value = notifications.value.filter((n) => n.id !== id);
    };
</script>

<style scoped>
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>
