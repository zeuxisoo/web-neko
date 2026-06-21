import api from '@/api';
import type { SettingsAttachmentIndexResponse, SettingsIndexResponse, SettingsPaginationIndexResponse } from '@/api/types';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';

const useSettingsStore = defineStore('settings', {
    state: () => ({
        attachment: null as SettingsAttachmentIndexResponse['data'] | null,
        pagination: null as SettingsPaginationIndexResponse['data'] | null,
    }),
    actions: {
        async fetchAll() {
            try {
                const { data, error } = await api.settings.all.index().json<SettingsIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    const result = data.value;
                    const allSettings = result.data;

                    this.attachment = allSettings.attachment;
                    this.pagination = allSettings.pagination;

                    return allSettings;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch settings in settings store');
            }
        },
        async updateAttachment(settings: SettingsAttachmentIndexResponse['data']) {
            this.attachment = settings;
        },
        async updatePagination(settings: SettingsPaginationIndexResponse['data']) {
            this.pagination = settings;
        },
    },
});

export default useSettingsStore;
