<script setup lang="ts">
import { VNode } from 'vue';

const props = defineProps<{
    title?: string;
}>();

defineSlots<{
    default(): VNode;
}>();
</script>

<template>
    <div class="mb-3 border-b border-gray-200 pb-3 dark:border-neutral-700">
        <h3 class="text-xl font-bold tracking-tight md:text-2xl">
            <slot name="default">
                {{ props.title }}
            </slot>
        </h3>
    </div>
</template>
