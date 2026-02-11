import { defineStore } from 'pinia';
import { onScopeDispose, ref } from 'vue';
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

    const instance = store();

    onScopeDispose(() => {
        instance.attachmentsStore.$dispose();
        instance.$dispose();
    });

    return instance;
};

export default useMemoStore;
