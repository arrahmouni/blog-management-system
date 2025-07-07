<template>
    <MainLayout>
        <router-view />
    </MainLayout>
    <Notifications />
</template>

<script setup>
    import Notifications from '../../admin/views/components/Notifications.vue';
    import MainLayout from './layouts/MainLayout.vue';
</script>
