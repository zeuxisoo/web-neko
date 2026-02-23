import useAgent from './useAgent';

class Attachment {
    index() {
        return useAgent<SettingsAttachmentIndexResponse>('settings/attachment/index').get();
    }

    update(payload: AttachmentSettingsPayload) {
        return useAgent<SettingsAttachmentUpdateResponse>('settings/attachment/update').put(payload);
    }
}

export default {
    get attachment() {
        return new Attachment();
    },
};
