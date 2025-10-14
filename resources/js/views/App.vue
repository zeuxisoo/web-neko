<script setup lang="ts">
const isLoggedIn = true;

import Home from '@/views/Home.vue';
import Login from '@/components/login/Login.vue';
</script>

<template>
    <Login v-if="!isLoggedIn" />
    <Home v-else />
</template>
