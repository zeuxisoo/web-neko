<script setup lang="ts">
import { Button } from '@/components/base/button';
import { useColorMode } from '@vueuse/core';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const mode = useColorMode();

const isDark = computed(() => mode.value === 'dark');

const toggleDark = () => {
    mode.value = mode.value === 'light' ? 'dark' : 'light';
};
</script>

<template>
    <Button variant="ghost" size="icon" class="hidden sm:flex" @click="toggleDark">
        <component :is="isDark ? Sun : Moon" />
        <span class="sr-only">Toggle appearance</span>
    </Button>
</template>
