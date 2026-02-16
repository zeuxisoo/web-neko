<script setup lang="ts">
import { PaperclipIcon } from 'lucide-vue-next';
import { Attachment } from '../types';
import AttachmentItem from './AttachmentItem.vue';

const props = defineProps<{
    attachments: Attachment[];
}>();

const emit = defineEmits<{
    up: [index: number];
    down: [index: number];
    remove: [index: number];
}>();
</script>

<template>
    <div class="boder-boder flex flex-col gap-1 rounded-sm border" v-if="props.attachments.length > 0">
        <div className="flex items-center gap-1 p-2 border-b border-border bg-muted/30 text-muted-foreground">
            <PaperclipIcon :size="12" />
            <span class="text-xs">Attachments ({{ props.attachments.length }})</span>
        </div>
        <div class="px-1">
            <AttachmentItem :attachments="props.attachments" @up="emit('up', $event)" @down="emit('down', $event)" @remove="emit('remove', $event)" />
        </div>
    </div>
</template>
