<script setup lang="ts">
import { Button } from '@/components/base/button';
import { cn } from '@/lib/utils';
import { Loader, SendHorizontal, X } from 'lucide-vue-next';
import { Attachment, Link } from '../types';
import LinkButton from './button/LinkButton.vue';
import UploadButton from './button/UploadButton.vue';

const props = defineProps<{
    isLoading: boolean;
    enableCancel: boolean;
    memo?: PulseMemoIndexResponse['data'][number];
}>();

const emit = defineEmits<{
    uploaded: [attachments: Attachment[]];
    linked: [link: Link];
    submit: [];
    cancel: [];
}>();
</script>

<template>
    <div class="flex w-full flex-row justify-between gap-2" :class="$attrs.class">
        <div class="flex flex-row gap-2">
            <UploadButton :memo="props.memo" @uploaded="emit('uploaded', $event)" />
            <LinkButton @linked="emit('linked', $event)" />
        </div>
        <div class="flex gap-0.5">
            <Button
                v-if="props.enableCancel"
                variant="secondary"
                :class="cn('flex flex-row items-center gap-1 rounded-md', { 'disabled:': isLoading })"
                @click="emit('cancel')"
            >
                <X />
            </Button>
            <Button :class="cn('flex flex-row items-center gap-1 rounded-md', { 'disabled:': isLoading })" @click="emit('submit')">
                <SendHorizontal :size="16" v-if="!props.isLoading" />
                <Loader :size="16" class="animate-spin" v-if="props.isLoading" />
                Submit
            </Button>
        </div>
    </div>
</template>
