import api from '@/api';
import { fillAttachments, humanSize, WhoopsHandler } from '@/utils';
import { computed, ComputedRef, ref } from 'vue';
import { Attachment } from '../types';

interface FileUploadOptions {
    maxFileSize: ComputedRef<number>;
    allowedTypes: ComputedRef<string[]>;
    onUploadCompleted: (attachments: Attachment[]) => void;
}

export default function useFileUpload(options: FileUploadOptions) {
    const fileInputRef = ref<HTMLInputElement>();
    const isUploading = ref<boolean>(false);

    const maxFileSize = computed(() => options.maxFileSize.value);
    const allowedTypes = computed(() => options.allowedTypes.value);

    const handleFileInputChange = async () => {
        if (!fileInputRef.value?.files || fileInputRef.value.files.length === 0 || isUploading.value) {
            return;
        }

        isUploading.value = true;

        const attachmentList: Attachment[] = [];
        try {
            // check file size and type before upload all
            for (const file of fileInputRef.value.files) {
                if (file.size > maxFileSize.value) {
                    throw new Error(`Error on "${file.name}" exceeds ${humanSize(maxFileSize.value)}, got ${humanSize(file.size)}`);
                }

                if (!allowedTypes.value.includes(file.type)) {
                    throw new Error(`Error on "${file.name}" file type is not image, got ${file.type}`);
                }
            }

            const formData = new FormData();
            for (const file of fileInputRef.value.files) {
                formData.append('files[]', file);
            }

            const { data, error } = await api.pulse.attachment.upload(formData).json<PulseAttachmentUploadResponse>();

            if (error.value) {
                throw error.value;
            }

            if (data && data.value) {
                const result = data.value;
                const attachments = result.data;

                for (const attachment of attachments) {
                    fillAttachments(attachmentList, attachment);
                }
            } else {
                throw error;
            }

            options.onUploadCompleted(attachmentList);
        } catch (e: unknown) {
            WhoopsHandler.handleError(e, 'Unknown error when upload attachment action in park pulse');
        } finally {
            isUploading.value = false;
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
