<script setup lang="ts">
import { Button } from '@/components/base/button';
import { ImageUpIcon, LoaderIcon } from 'lucide-vue-next';
import useFileUpload from '../../composables/useFileUpload';
import { Attachment } from '../../types';

const props = defineProps<{
    onUploaded: (attachment: Attachment[]) => void;
}>();

const { fileInputRef, isUploading, handleFileInputChange, handleUploadClick } = useFileUpload({
    maxFileSize: 10 * 1024 * 1024, // MB
    allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
    onUploadCompleted: (uploadedAttachments: Attachment[]) => {
        props.onUploaded(uploadedAttachments);
    },
});
</script>

<template>
    <div>
        <Button class="rounded-md" @click="handleUploadClick" :disabled="isUploading">
            <component :is="isUploading ? LoaderIcon : ImageUpIcon" :size="24" :class="{ 'animate-spin': isUploading }" />
        </Button>
    </div>
    <input class="hidden" ref="fileInputRef" @change="handleFileInputChange" :disabled="isUploading" type="file" multiple="true" accept="image/*" />
</template>
