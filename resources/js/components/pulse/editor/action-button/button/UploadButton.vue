<script setup lang="ts">
import { Button } from '@/components/base/button';
import { useSettingsStore } from '@/stores';
import { ImageUpIcon, LoaderIcon } from 'lucide-vue-next';
import { computed, onMounted } from 'vue';
import useFileUpload from '../../composables/useFileUpload';
import { Attachment } from '../../types';

const props = defineProps<{
    memo?: PulseMemoIndexResponse['data'][number];
}>();

const emit = defineEmits<{
    uploaded: [attachments: Attachment[]];
}>();

const settingsStore = useSettingsStore();

const allowedTypes = computed(() => {
    // convert extensions ['jpeg', 'jpg', 'png'] to MIME types ['image/jpeg', 'image/jpg', 'image/png']
    // jpg and jpeg both map to image/jpeg in browser's file.type
    return (settingsStore.attachment?.allowed_mimes ?? []).map((ext: string) => {
        return ext === 'jpg' ? 'image/jpeg' : `image/${ext}`;
    });
});

const maxFileSize = computed(() => {
    return (settingsStore.attachment?.max_size_kb ?? 0) * 1024;
});

onMounted(async () => {
    await settingsStore.fetchAttachment();
});

const { fileInputRef, isUploading, handleFileInputChange, handleUploadClick } = useFileUpload({
    maxFileSize: maxFileSize,
    allowedTypes: allowedTypes,
    attachmentsStoreId: props.memo?.id.toString(),
    onUploadCompleted: (uploadedAttachments: Attachment[]) => {
        emit('uploaded', uploadedAttachments);
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
