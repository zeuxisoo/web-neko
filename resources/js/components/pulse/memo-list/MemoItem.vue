<script setup lang="ts">
import api from '@/api';
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { useAttachmentsStore, useLinksStore, useMemosStore, useTagsStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import Editor from '../editor';
import { SubmitData } from '../editor/types';
import MemoBody from './memo/MemoBody.vue';
import MemoHeader from './memo/MemoHeader.vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const isEditing = ref(false);
const isLoading = ref(false);
const editor = ref('');
const extractedTags = ref<string[]>([]);

const memosStore = useMemosStore();
const tagsStore = useTagsStore();
const attachmentsStore = useAttachmentsStore(String(props.memo.id));
const linkStore = useLinksStore(String(props.memo.id));

const handleExtractedTags = (tags: string[]) => {
    extractedTags.value = tags;
};

const handleEdit = () => {
    isEditing.value = !isEditing.value;
};

const handleCancel = () => {
    isEditing.value = false;
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

        const formData = validator.form('pulse.memo.update').validate({
            id: props.memo.id,
            content: editor.value,
            tags: extractedTags.value,
            attachments: attachmentList,
            links: linkList,
        });

        const { data, error } = await api.pulse.memo.update(formData as PulseMemoUpdatePayload).json<PulseMemoStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const updatedMemo = result.data;

            memosStore.update(updatedMemo);

            isEditing.value = false;

            toast.info('Memo updated');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when update memo action');
    } finally {
        isLoading.value = false;
    }
};

watch(
    () => isEditing.value,
    () => {
        if (isEditing.value) {
            editor.value = props.memo.content;

            // initialize extractedTags from saved memo tags for consistency
            // note: TagList will also extract tags from editor content, but this ensures
            // the initial state matches what was saved (handles edge cases/parsing issues)
            extractedTags.value = props.memo.tags.map((tag) => tag.name);

            attachmentsStore.attachments = props.memo.attachments;
            linkStore.links = props.memo.links;
        }
    },
);
</script>

<template>
    <Editor
        v-if="isEditing"
        v-model="editor"
        class="border-2 border-accent-foreground/30"
        :isLoading="isLoading"
        :enableCancel="true"
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
        @cancel="handleCancel"
    />
    <Card class="gap-2" v-else>
        <CardHeader>
            <MemoHeader :item="props.memo" />
        </CardHeader>
        <CardContent>
            <MemoBody :memo="props.memo" @edit="handleEdit" />
        </CardContent>
    </Card>
</template>
