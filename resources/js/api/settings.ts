import useAgent from './useAgent';

class Settings {
    index() {
        return useAgent<SettingsIndexResponse>('settings/index').get();
    }
}

class Attachment {
    index() {
        return useAgent<SettingsAttachmentIndexResponse>('settings/attachment/index').get();
    }

    update(payload: SettingsAttachmentUpdatePayload) {
        return useAgent<SettingsAttachmentUpdateResponse>('settings/attachment/update').post(payload);
    }
}

class Pagination {
    index() {
        return useAgent<SettingsPaginationIndexResponse>('settings/pagination/index').get();
    }

    update(payload: SettingsPaginationUpdatePayload) {
        return useAgent<SettingsPaginationUpdateResponse>('settings/pagination/update').post(payload);
    }
}

export default {
    get all() {
        return new Settings();
    },
    get attachment() {
        return new Attachment();
    },
    get pagination() {
        return new Pagination();
    },
};
