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

class Memo {
    store(payload: PulseMemoStorePayload) {
        return useAgent<PulseMemoStoreResponse>('pulse/memo/store').post(payload);
    }

    update(payload: PulseMemoUpdatePayload) {
        return useAgent<PulseMemoStoreResponse>('pulse/memo/update').post(payload);
    }

    index(payload: PulseMemoIndexPayload) {
        const entrypoint = 'pulse/memo/index';

        const params = new URLSearchParams();
        params.append('page', payload.page.toString());

        if (payload.tag) {
            params.append('tag', payload.tag);
        }

        const url = entrypoint + '?' + params.toString();

        return useAgent<PulseMemoIndexResponse>(url).get();
    }

    destroy(id: number) {
        return useAgent<PulseMemoDestroyResponse>('pulse/memo/destroy/' + id).get();
    }
}

class Bookmark {
    add(memoId: number) {
        return useAgent<PulseBookmarkAddResponse>('pulse/bookmark/add/' + memoId).get();
    }

    remove(memoId: number) {
        return useAgent<PulseBookmarkRemoveResponse>('pulse/bookmark/remove/' + memoId).get();
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
    get memo() {
        return new Memo();
    },
    get bookmark() {
        return new Bookmark();
    },
    get tag() {
        return new Tag();
    },
};
