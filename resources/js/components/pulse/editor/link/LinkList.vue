<script setup lang="ts">
import { LinkIcon } from 'lucide-vue-next';
import { Link } from '../types';
import LinkItem from './LinkItem.vue';

const props = defineProps<{
    links: Link[];
}>();

const emit = defineEmits<{
    remove: [index: number];
}>();
</script>

<template>
    <div class="boder-boder flex flex-col gap-1 rounded-sm border" v-if="props.links.length > 0">
        <div className="flex items-center gap-1 p-2 border-b border-border bg-muted/30 text-muted-foreground">
            <LinkIcon :size="12" />
            <span class="text-xs">Links ({{ props.links.length }})</span>
        </div>
        <div class="px-1">
            <LinkItem :links="props.links" @remove="emit('remove', $event)" />
        </div>
    </div>
</template>
