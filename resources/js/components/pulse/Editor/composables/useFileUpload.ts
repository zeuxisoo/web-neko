import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { ref } from 'vue';
import { Attachment } from '../types';

interface FileUploadOptions {
    previewUrl: string;
    maxFileSize: number;
    allowedTypes: string[];
    onUploadCompleted: (attachments: Attachment[]) => void;
}

function humanSize(bytes: number) {
    if (bytes === 0) {
        return '0 Bytes';
    }

    const units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));

    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + units[i];
}

function addSuffixToFileName(filename: string, suffix: string) {
    const dotIndex = filename.lastIndexOf('.');

    // if no dot is found or it's the first character (hidden file), append to end
    if (dotIndex <= 0) return filename + suffix;

    return filename.slice(0, dotIndex) + suffix + filename.slice(dotIndex);
}

function rtrimSlash(path: string) {
    return path.endsWith('/') ? path.slice(0, -1) : path;
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

            if (data && data.value) {
                const result = data.value;
                const attachments = result.data;

                for (const attachment of attachments) {
                    attachmentList.push({
                        filename: attachment.filename,
                        size: attachment.size,
                        type: attachment.mime_type || 'application/octet-stream',
                        url: rtrimSlash(options.previewUrl) + '/' + addSuffixToFileName(attachment.filename, '_cover'),
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
