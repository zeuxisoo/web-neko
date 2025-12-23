<script setup lang="ts">
const isLoggedIn = false;

import { Toaster } from '@/components/base/sonner';
import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';
</script>

<template>
    <Login v-if="!isLoggedIn" />
    <Home v-else />
    <Toaster position="bottom-center" :close-button="true" close-button-position="top-right" />
</template>
