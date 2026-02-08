<script setup lang="ts">
import AlertDialog from '@/components/alert-dialog/AlertDialog.vue';
import { Toaster } from '@/components/base/sonner';
import { useAuthStore } from '@/stores';
import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';

const authStore = useAuthStore();

// prettier-ignore
(() => {
    authStore.restoreAuth();
})();
</script>

<template>
    <Login v-if="!authStore.isAuthenticated" />
    <Home v-else />
    <AlertDialog />
    <Toaster position="bottom-center" :close-button="true" close-button-position="top-right" />
</template>
