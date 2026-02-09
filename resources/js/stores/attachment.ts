import api from '@/api';
import { Attachment } from '@/components/pulse/editor/types';
import { fillAttachments, WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';
import { toast } from 'vue-sonner';

const useAttachmentStore = defineStore('attachment', {
    state: () => ({
        attachments: [] as Attachment[],
    }),
    actions: {
        async fetchUnsaved() {
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
                WhoopsHandler.handleError(e, 'Unknown error when remove attachment action in park pulse');
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

export default useAttachmentStore;
