<script setup lang="ts">
import { Button } from '@/components/base/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/base/dropdown-menu';
import { useStorage } from '@vueuse/core';
import { SwatchBook } from 'lucide-vue-next';
import { onMounted } from 'vue';

const themes = {
    eggplant: 'Eggplant',
    mushroom: 'Mushroom',
};

const activeTheme = useStorage<string>('active-theme', '');

onMounted(() => {
    if (activeTheme.value !== '') {
        document.documentElement.classList.add(`${activeTheme.value}`);
    }
});

const setTheme = (name: string) => {
    const oldTheme = activeTheme.value;
    const newTheme = `theme-${name}`;

    changeTheme(oldTheme, newTheme);
};

const changeTheme = (from: string, to: string) => {
    const html = document.documentElement;

    if (from !== '') {
        html.classList.remove(from);
    }
    html.classList.add(to);

    activeTheme.value = to;
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <Button variant="ghost" size="icon" class="hidden sm:flex">
                <SwatchBook />
                <span class="sr-only">Toggle theme</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuLabel>Theme List</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <template v-for="(name, key) in themes">
                <DropdownMenuItem @click="setTheme(key)">{{ name }}</DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
