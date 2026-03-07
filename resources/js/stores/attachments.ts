import api from '@/api';
import { fillAttachments, humanSize, WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';
import { onScopeDispose } from 'vue';
import { toast } from 'vue-sonner';

export interface UploadOptions {
    maxFileSize?: number;
    allowedTypes?: string[];
}

const useAttachmentsStore = (id: string = 'default') => {
    const store = defineStore(`attachments-${id}`, {
        state: () => ({
            attachments: [] as Attachment[],
            isUploading: false as boolean,
        }),
        actions: {
            async fetchUnsaved() {
                this.attachments = [];

                try {
                    const { data, error } = await api.pulse.attachment.unsaved().json<PulseAttachmentUploadResponse>();

                    if (error.value) {
                        throw error.value;
                    }

                    if (data && data.value) {
                        const result = data.value;
                        const attachmentList = result.data;

                        for (const attachment of attachmentList) {
                            fillAttachments(this.attachments, attachment);
                        }
                    } else {
                        throw error.value;
                    }
                } catch (e: unknown) {
                    WhoopsHandler.handleError(e, 'Unknown error when fetch unsaved attachment list action in attachment store');
                }
            },
            async removeAttachment(index: number) {
                try {
                    const attachment = this.attachments[index];

                    const { data, error } = await api.pulse.attachment.destroy(attachment.id).json<PulseAttachmentDestroyResponse>();

                    if (error.value) {
                        throw error.value;
                    }

                    if (data && data.value) {
                        const result = data.value;
                        const message = result.message;

                        toast.info(message);

                        this.attachments.splice(index, 1);
                    } else {
                        throw error.value;
                    }
                } catch (e: unknown) {
                    WhoopsHandler.handleError(e, 'Unknown error when remove attachment action in attachment store');
                }
            },
            async uploadFiles(files: FileList | File[], options: UploadOptions = {}): Promise<Attachment[]> {
                const maxFileSize = options.maxFileSize ?? Infinity;
                const allowedTypes = options.allowedTypes ?? [];

                if (this.isUploading) {
                    return [];
                }

                this.isUploading = true;

                try {
                    const attachmentList: Attachment[] = [];
                    const fileArray = Array.from(files);

                    // validate file size and type before upload
                    for (const file of fileArray) {
                        if (file.size > maxFileSize) {
                            throw new Error(`Error on "${file.name}" exceeds ${humanSize(maxFileSize)}, got ${humanSize(file.size)}`);
                        }

                        if (allowedTypes.length > 0 && !allowedTypes.includes(file.type)) {
                            throw new Error(`Error on "${file.name}" file type is not allowed, got ${file.type}`);
                        }
                    }

                    const formData = new FormData();
                    for (const file of fileArray) {
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

                        return attachmentList;
                    } else {
                        throw error.value;
                    }
                } catch (e: unknown) {
                    WhoopsHandler.handleError(e, 'Unknown error when upload attachment action in attachment store');
                    return [];
                } finally {
                    this.isUploading = false;
                }
            },
            onUploaded(files: Attachment[]) {
                this.attachments = this.attachments.concat(files);
            },
            onAttachmentUp(index: number) {
                if (index <= 0) return;

                const item = this.attachments[index];

                this.attachments[index] = this.attachments[index - 1];
                this.attachments[index - 1] = item;
            },
            onAttachmentDown(index: number) {
                if (index >= this.attachments.length - 1) return;

                const item = this.attachments[index];

                this.attachments[index] = this.attachments[index + 1];
                this.attachments[index + 1] = item;
            },
        },
    });

    const instance = store();

    onScopeDispose(() => {
        instance.$dispose();
    });

    return instance;
};

export default useAttachmentsStore;
