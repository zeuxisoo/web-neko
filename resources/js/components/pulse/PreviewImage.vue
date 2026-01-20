<script setup lang="ts">
import { Skeleton } from '@/components/base/skeleton';
import { cn } from '@/lib/utils';
import { ref } from 'vue';

const props = defineProps<{
    image: { src: string; title: string };
}>();

const isLoaded = ref(false);

const handleLoad = () => {
    isLoaded.value = true;
};
</script>

<template>
    <div class="flex justify-center" v-if="!isLoaded">
        <Skeleton class="aspect-square max-h-48 w-48 cursor-pointer rounded-lg border" />
    </div>
    <img
        :src="props.image.src"
        :title="props.image.title"
        :class="cn('hidden max-h-48 cursor-pointer rounded-lg border object-cover', { block: isLoaded })"
        decoding="async"
        loading="lazy"
        @load="handleLoad"
    />
</template>
