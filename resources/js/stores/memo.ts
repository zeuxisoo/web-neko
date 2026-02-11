import { defineStore } from 'pinia';
import { ref } from 'vue';
import useAttachmentsStore from './attachments';

const useMemoStore = (id: number) => {
    const store = defineStore(`memo-${id}`, () => {
        const memo = ref<Memo>();
        const attachmentsStore = useAttachmentsStore(String(id));

        function setMemo(m: Memo) {
            memo.value = m;
        }

        function setAttachments(attachments: Attachment[]) {
            attachmentsStore.attachments = attachments;
        }

        return {
            attachmentsStore,
            setMemo,
            setAttachments,
        };
    });

    return store();
};

export default useMemoStore;
