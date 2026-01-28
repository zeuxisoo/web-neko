import useAgent from './useAgent';

class Attachment {
    upload(formData: FormData) {
        return useAgent<PulseAttachmentUploadResponse>('pulse/attachment/upload').post(formData);
    }
    destroy(id: number) {
        return useAgent<PulseAttachmentDestroyResponse>('pulse/attachment/destroy/' + id).get();
    }
    unsaved() {
        return useAgent<PulseAttachmentUploadResponse>('pulse/attachment/unsaved').get();
    }
}

class Tag {
    all() {
        return useAgent<PulseTagResponse>('pulse/tag/all').get();
    }
}

export default {
    get attachment() {
        return new Attachment();
    },
    get tag() {
        return new Tag();
    },
};
