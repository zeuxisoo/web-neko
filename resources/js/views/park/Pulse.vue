<script setup lang="ts">
import api from '@/api';
import { Editor, MemoList } from '@/components/pulse';
import { SubmitData } from '@/components/pulse/editor/types';
import { useLinksStore } from '@/stores';
import useAttachmentsStore from '@/stores/attachments';
import useMemosStore from '@/stores/memos';
import useTagsStore from '@/stores/tags';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { toast } from 'vue-sonner';

const isLoading = ref(false);
const editor = ref('');
const extractedTags = ref<string[]>([]);

const route = useRoute();
const tagsStore = useTagsStore();
const attachmentsStore = useAttachmentsStore();
const linkStore = useLinksStore();
const memosStore = useMemosStore();

onMounted(() => Promise.all([tagsStore.fetch(), attachmentsStore.fetchUnsaved(), linkStore.fetchUnsaved()]));

const handleExtractedTags = (tags: string[]) => {
    extractedTags.value = tags;
};

const handleSubmit = async (_: SubmitData) => {
    const attachmentList = attachmentsStore.attachments.map((attachment, index) => {
        return {
            id: attachment.id,
            filename: attachment.filename,
            sort_order: index,
        };
    });

    const linkList = linkStore.links.map((link, index) => {
        return {
            id: link.id,
            url: link.url,
        };
    });

    try {
        isLoading.value = true;

        const formData = validator.form('pulse.memo.store').validate({
            content: editor.value,
            tags: extractedTags.value,
            attachments: attachmentList,
            links: linkList,
        });

        const { data, error } = await api.pulse.memo.store(formData as PulseMemoStorePayload).json<PulseMemoStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const memo = result.data;

            memosStore.prepend(memo);

            // cleanup editor content
            editor.value = '';

            // cleanup extracted tag in pulse and attachments in store
            extractedTags.value = [];
            attachmentsStore.attachments = [];
            linkStore.links = [];

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

        memosStore.fetchList(page, tag);
    },
    { immediate: true },
);
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor
            v-model="editor"
            :isLoading="isLoading"
            :enableCancel="false"
            :tags="tagsStore.tags"
            :attachments="attachmentsStore.attachments"
            :links="linkStore.links"
            @uploaded="attachmentsStore.onUploaded"
            @attachmentUp="attachmentsStore.onAttachmentUp"
            @attachmentDown="attachmentsStore.onAttachmentDown"
            @attachmentRemove="attachmentsStore.removeAttachment"
            @linked="linkStore.onLinked"
            @linkRemove="linkStore.removeLink"
            @extractedTags="handleExtractedTags"
            @submit="handleSubmit"
        />
        <MemoList :is-loading="memosStore.isLoading" :memos="memosStore.memos" />
    </div>
</template>
