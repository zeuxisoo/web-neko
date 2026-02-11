<script setup lang="ts">
import { extractHashTags } from '@/utils';
import { ref, watch } from 'vue';

const props = defineProps<{
    editor: string;
    onExtracted: (tags: string[]) => void;
}>();

const tags = ref<string[]>([]);

watch(
    () => props.editor,
    (newVal, oldVal) => {
        tags.value = extractHashTags(newVal);

        props.onExtracted(tags.value);
    },
    { immediate: true },
);
</script>

<template>
    <div class="flex flex-row gap-1">
        <div class="rounded-md border border-border p-0.5 text-sm text-muted-foreground hover:bg-accent/50" v-for="(tag, index) in tags" :key="index">
            {{ tag }}
        </div>
    </div>
</template>
