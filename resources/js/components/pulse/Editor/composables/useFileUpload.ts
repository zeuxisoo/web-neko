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
                    throw new Error(`${file.name} exceeds ${options.maxFileSize} MB, got ${(file.size / 1024 / 1024).toFixed(2)} MB`);
                }

                if (!options.allowedTypes.includes(file.type)) {
                    throw new Error(`${file.name} file type is not image`);
                }
            }

            for (const file of fileInputRef.value.files) {
                const formData = new FormData();
                formData.append('image', file);

                /* TODO: connect backend api
                const response = await utils.uploadImage({
                    mode: 1,
                    baseUrl: 'http://localhost:8080/',
                    uploadUri: 'image.php?action=upload',
                    formData: formData,
                });

                if (!response.ok) {
                    const errorDetails = await response.json();
                    alert(errorDetails.error || `HTTP error! status: ${response.status}`);
                    continue;
                }

                const result = await response.json();

                attachmentList.push({
                    filename: file.name,
                    size: file.size,
                    type: file.type || 'application/octet-stream',
                    url: result.url,
                });
                 */
                attachmentList.push({
                    filename: file.name,
                    size: file.size,
                    type: file.type || 'application/octet-stream',
                    url: 'todo://attachment.url',
                });
            }

            options.onUploadCompleted(attachmentList);
        } catch (e: any) {
            console.error('Upload error:', e);
            alert(e.message);
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
