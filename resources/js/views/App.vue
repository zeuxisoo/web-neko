<script setup lang="ts">
const isLoggedIn = true;

import Home from '@/components/dashboard/Home.vue';
import LoginForm from '@/components/dashboard/LoginForm.vue';
</script>

<template>
    <div class="flex h-screen w-full items-center justify-center px-4" v-if="!isLoggedIn">
        <LoginForm />
    </div>
    <Home v-else />
</template>
