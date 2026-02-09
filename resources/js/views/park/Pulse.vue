<script setup lang="ts">
import api from '@/api';
import { Editor, MemoList } from '@/components/pulse';
import { SubmitData } from '@/components/pulse/editor/types';
import { useAttachmentStore, useMemoStore, useTagStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { toast } from 'vue-sonner';

const isLoading = ref(false);
const editor = ref('');
const extractedTags = ref<string[]>([]);

const route = useRoute();
const tagStore = useTagStore();
const attachmentStore = useAttachmentStore();
const memoStore = useMemoStore();

onMounted(() => Promise.all([tagStore.fetch(), attachmentStore.fetchUnsaved()]));

const handleExtractedTags = (tags: string[]) => {
    extractedTags.value = tags;
};

const handleSubmit = async (_: SubmitData) => {
    const attachmentList = attachmentStore.attachments.map((attachment, index) => {
        return {
            id: attachment.id,
            filename: attachment.filename,
            sort_order: index,
        };
    });

    try {
        isLoading.value = true;

        const formData = validator.form('pulse.memo.store').validate({
            content: editor.value,
            tags: extractedTags.value,
            attachments: attachmentList,
        });

        const { data, error } = await api.pulse.memo.store(formData as PulseMemoStorePayload).json<PulseMemoStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const memo = result.data;

            memoStore.prepend(memo);

            editor.value = '';
            tagStore.tags = {};
            extractedTags.value = [];
            attachmentStore.attachments = [];

            toast.info('Memo created');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle account avatar save action');
    } finally {
        isLoading.value = false;
    }
};

watch(
    [() => route.query.page, () => route.query.tag],
    ([pageNewVal, tagNewVal]) => {
        const page = pageNewVal ? Number(pageNewVal) : 1;
        const tag = tagNewVal ? String(tagNewVal) : '';
        memoStore.fetchList(page, tag);
    },
    { immediate: true },
);
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor
            v-model="editor"
            :isLoading="isLoading"
            :tags="tagStore.tags"
            :attachments="attachmentStore.attachments"
            @uploaded="attachmentStore.onUploaded"
            @attachmentUp="attachmentStore.onAttachmentUp"
            @attachmentDown="attachmentStore.onAttachmentDown"
            @attachmentRemove="attachmentStore.removeAttachment"
            @extractedTags="handleExtractedTags"
            @submit="handleSubmit"
        />
        <MemoList :memos="memoStore.memos" />
    </div>
</template>
