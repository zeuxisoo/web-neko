<script setup lang="ts">
import { Input } from '@/components/base/input';
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <Input data-slot="sidebar-input" data-sidebar="input" :class="cn('h-8 w-full bg-background shadow-none', props.class)">
        <slot />
    </Input>
</template>
