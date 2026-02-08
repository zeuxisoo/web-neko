<script setup lang="ts">
import { Skeleton } from '@/components/base/skeleton';
import { cn } from '@/lib/utils';
import { ref } from 'vue';

const props = defineProps<{
    image: { src: string; title: string };
}>();

const isLoading = ref(true);

const handleLoad = () => {
    isLoading.value = false;
};
</script>

<template>
    <div :class="'relative aspect-square overflow-hidden rounded-md'">
        <Skeleton class="absolute inset-0 z-10 h-full w-full" />

        <img
            :src="props.image.src"
            :title="props.image.title"
            :class="cn('h-full w-full cursor-pointer object-cover transition-opacity duration-300', isLoading ? 'opacity-0' : 'opacity-100')"
            decoding="async"
            loading="lazy"
            @load="handleLoad"
        />
    </div>
</template>
