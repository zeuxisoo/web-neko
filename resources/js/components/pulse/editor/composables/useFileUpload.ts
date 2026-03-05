import { useAttachmentsStore } from '@/stores';
import type { UploadOptions } from '@/stores/attachments';
import { computed, ComputedRef, ref } from 'vue';
import { Attachment } from '../types';

interface FileUploadOptions {
    maxFileSize: ComputedRef<number>;
    allowedTypes: ComputedRef<string[]>;
    onUploadCompleted?: (attachments: Attachment[]) => void;
    attachmentsStoreId?: string;
}

export default function useFileUpload(options: FileUploadOptions) {
    const fileInputRef = ref<HTMLInputElement>();
    const attachmentsStore = useAttachmentsStore(options.attachmentsStoreId ?? 'default');

    const maxFileSize = computed(() => options.maxFileSize.value);
    const allowedTypes = computed(() => options.allowedTypes.value);
    const isUploading = computed(() => attachmentsStore.isUploading);

    const handleFileInputChange = async () => {
        if (!fileInputRef.value?.files || fileInputRef.value.files.length === 0 || attachmentsStore.isUploading) {
            return;
        }

        const uploadOptions: UploadOptions = {
            maxFileSize: maxFileSize.value,
            allowedTypes: allowedTypes.value,
        };

        const uploadedAttachments = await attachmentsStore.uploadFiles(fileInputRef.value.files, uploadOptions);

        // call the onUploadCompleted callback with uploaded attachments
        if (options.onUploadCompleted && uploadedAttachments.length > 0) {
            options.onUploadCompleted(uploadedAttachments);
        }

        // clear the input so the same file can be selected again
        if (fileInputRef.value) {
            fileInputRef.value.value = '';
        }
    };

    const handleUploadClick = () => {
        fileInputRef.value?.click();
    };

    return {
        fileInputRef,
        isUploading,
        handleFileInputChange,
        handleUploadClick,
    };
}
