<template>
    <div class="comment-item mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-4">
                <div
                    class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10"
                />
            </div>

            <div class="flex-grow">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-gray-900">
                            {{ userFullName }}
                        </h4>
                        <span class="text-sm text-gray-500">
                            {{ comment.created_at  }}
                        </span>
                    </div>

                    <p class="mt-2 text-gray-700">{{ comment.comment  }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        comment: {
            type: Object,
            required: true,
        },
    });

    const userFullName = computed(() => {
        return props.comment.user?.full_name || "Unknown User";
    });
</script>
