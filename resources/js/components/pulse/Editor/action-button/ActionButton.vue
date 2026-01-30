<script setup lang="ts">
import { Button } from '@/components/base/button';
import { cn } from '@/lib/utils';
import { Loader, SendHorizontal } from 'lucide-vue-next';
import { Attachment } from '../types';
import UploadButton from './button/UploadButton.vue';

const props = defineProps<{
    isLoading: boolean;
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
            <Button :class="cn('flex flex-row items-center gap-1 rounded-md', { 'disabled:': isLoading })" @click="props.onSubmit">
                <SendHorizontal :size="16" v-if="!props.isLoading" />
                <Loader :size="16" class="animate-spin" v-if="props.isLoading" />
                Submit
            </Button>
        </div>
    </div>
</template>
