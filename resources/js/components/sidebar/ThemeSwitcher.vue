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
import { Check, SwatchBook } from 'lucide-vue-next';
import { onMounted } from 'vue';

const themes = {
    default: 'Default',
    eggplant: 'Eggplant',
    mushroom: 'Mushroom',
};

const activeTheme = useStorage<string>('active-theme', '');

onMounted(() => {
    if (activeTheme.value !== '') {
        document.documentElement.classList.add(addThemePrefix(activeTheme.value));
    }
});

const addThemePrefix = (name: string) => `theme-${name}`;

const setTheme = (name: string) => {
    const oldTheme = activeTheme.value;
    const newTheme = name;

    changeTheme(oldTheme, newTheme);
};

const changeTheme = (from: string, to: string) => {
    const html = document.documentElement;

    if (from !== '') {
        html.classList.remove(addThemePrefix(from));
    }
    html.classList.add(addThemePrefix(to));

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
                <DropdownMenuItem class="flex" @click="setTheme(key)">
                    <div :class="[`theme-${key}`, 'bg-base-100 grid grid-cols-2 gap-0.5 rounded-sm p-1 shadow-sm']">
                        <div class="background size-1 rounded-md"></div>
                        <div class="size-1 rounded-md bg-primary"></div>
                        <div class="size-1 rounded-md bg-secondary"></div>
                        <div class="size-1 rounded-md bg-accent"></div>
                    </div>
                    <div class="flex-1">{{ name }}</div>
                    <Check v-if="key === activeTheme" />
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
