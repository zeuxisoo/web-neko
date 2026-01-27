import api from '@/api';
import { humanSize, WhoopsHandler } from '@/utils';
import { ref } from 'vue';
import { Attachment } from '../types';

interface FileUploadOptions {
    maxFileSize: number;
    allowedTypes: string[];
    onUploadCompleted: (attachments: Attachment[]) => void;
}

export default function useFileUpload(options: FileUploadOptions) {
    const fileInputRef = ref<HTMLInputElement>();
    const isUploading = ref<boolean>(false);

    const handleFileInputChange = async () => {
        if (!fileInputRef.value?.files || fileInputRef.value.files.length === 0 || isUploading.value) {
            return;
        }

        isUploading.value = true;

        const attachmentList: Attachment[] = [];
        try {
            // check file size and type befoe upload all
            for (const file of fileInputRef.value.files) {
                if (file.size > options.maxFileSize) {
                    throw new Error(`Error on "${file.name}" exceeds ${humanSize(options.maxFileSize)}, got ${humanSize(file.size)}`);
                }

                if (!options.allowedTypes.includes(file.type)) {
                    throw new Error(`Error on "${file.name}" file type is not image`);
                }
            }

            const formData = new FormData();
            for (const file of fileInputRef.value.files) {
                formData.append('files[]', file);
            }

            const { data, error } = await api.pulse.attachment.upload(formData).json<PulseAttachmentResponse>();

            if (error.value) {
                throw error.value;
            }

            if (data && data.value) {
                const result = data.value;

                const attachments = result.data;

                for (const attachment of attachments) {
                    attachmentList.push({
                        id: attachment.id,
                        filename: attachment.filename,
                        original_name: attachment.original_name,
                        size: attachment.size,
                        type: attachment.mime_type || 'application/octet-stream',
                        links: attachment.links,
                    });
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
