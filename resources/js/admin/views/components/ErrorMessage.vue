<template>
    <div v-if="errors && errors[field] && errors[field].length" class="text-red-600 text-sm mt-1 flex font-medium">
        {{ errors[field][0] }}
    </div>
</template>

<script setup>
    defineProps({
        errors: {
            type: Object,
            required: true,
        },
        field: {
            type: String,
            required: true,
        },
    });
</script>
