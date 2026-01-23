import useAgent from './useAgent';

class Attachment {
    upload(formData: FormData) {
        return useAgent<PulseAttachmentResponse>('pulse/attachment/upload').post(formData);
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
