import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';

const useSettingsStore = defineStore('settings', {
    state: () => ({
        attachment: null as SettingsAttachmentIndexResponse['data'] | null,
    }),
    actions: {
        async fetchAttachment() {
            try {
                const { data, error } = await api.settings.attachment.index().json<SettingsAttachmentIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    const result = data.value;
                    const attachmentSettings = result.data;

                    this.attachment = attachmentSettings;

                    return attachmentSettings;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch attachment settings in settings store');
            }
        },
        async updateAttachment(settings: SettingsAttachmentUpdateResponse['data']) {
            this.attachment = settings;
        },
    },
});

export default useSettingsStore;
