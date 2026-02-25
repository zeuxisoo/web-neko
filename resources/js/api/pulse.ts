import useAgent from './useAgent';

class Attachment {
    index(payload: PulseAttachmentIndexPayload) {
        const entrypoint = 'pulse/attachment/index';

        const params = new URLSearchParams();
        params.append('page', payload.page.toString());

        if (payload.cursor) {
            params.append('cursor', payload.cursor.toString());
        }

        const url = entrypoint + '?' + params.toString();

        return useAgent<PulseAttachmentIndexResponse>(url).get();
    }

    upload(formData: FormData) {
        return useAgent<PulseAttachmentUploadResponse>('pulse/attachment/upload').post(formData);
    }

    destroy(id: number) {
        return useAgent<PulseAttachmentDestroyResponse>('pulse/attachment/destroy/' + id).get();
    }

    unsaved() {
        return useAgent<PulseAttachmentUnsavedResponse>('pulse/attachment/unsaved').get();
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

    show(id: number) {
        return useAgent<PulseMemoShowResponse>('pulse/memo/show/' + id).get();
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

class Comment {
    index(payload: PulseCommentIndexPayload) {
        const entrypoint = 'pulse/comment/index/' + payload.memo_id;

        const params = new URLSearchParams();
        params.append('page', payload.page.toString());

        const url = entrypoint + '?' + params.toString();

        return useAgent<PulseCommentIndexResponse>(url).get();
    }

    store(payload: PulseCommentStorePayload) {
        return useAgent<PulseCommentStoreResponse>('pulse/comment/store').post(payload);
    }
}

class Link {
    store(payload: PulseLinkStorePayload) {
        return useAgent<PulseLinkStoreResponse>('pulse/link/store').post(payload);
    }

    destroy(id: number) {
        return useAgent<PulseLinkDestroyResponse>('pulse/link/destroy/' + id).get();
    }

    unsaved() {
        return useAgent<PulseLinkUnsavedResponse>('pulse/link/unsaved').get();
    }

    fetch(payload: PulseLinkFetchPayload) {
        return useAgent<PulseLinkFetchResponse>('pulse/link/fetch').post(payload);
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
    get comment() {
        return new Comment();
    },
    get link() {
        return new Link();
    },
};
