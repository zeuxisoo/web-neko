<script setup lang="ts">
import { Skeleton } from '@/components/base/skeleton';
import { cn } from '@/lib/utils';
import { ref } from 'vue';

const emit = defineEmits<{
    click: [index: number];
}>();

const props = defineProps<{
    image: { src: string; title: string };
    index?: number;
}>();

const isLoading = ref(true);

const handleLoad = () => {
    isLoading.value = false;
};

const handleClick = () => {
    emit('click', props.index ?? 0);
};
</script>

<template>
    <div :class="'relative aspect-square overflow-hidden rounded-md'" @click="handleClick">
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
