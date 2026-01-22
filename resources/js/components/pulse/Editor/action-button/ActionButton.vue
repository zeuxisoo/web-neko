<script setup lang="ts">
import { Button } from '@/components/base/button';
import { SendHorizontal } from 'lucide-vue-next';
import { Attachment } from '../types';
import UploadButton from './button/UploadButton.vue';

const props = defineProps<{
    onUploaded: (attachments: Attachment[]) => void;
    onSubmit: () => void;
}>();
</script>

<template>
    <div class="flex w-full flex-row justify-between gap-2" :class="$attrs.class">
        <div class="flex flex-row gap-2">
            <UploadButton :onUploaded="props.onUploaded" />
        </div>
        <div>
            <Button class="flex flex-row items-center gap-1 rounded-md" @click="props.onSubmit"> <SendHorizontal :size="16" /> Submit </Button>
        </div>
    </div>
</template>
