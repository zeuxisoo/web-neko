<script setup lang="ts">
const isLoggedIn = true;

import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';
</script>

<template>
    <Login v-if="!isLoggedIn" />
    <Home v-else />
</template>
