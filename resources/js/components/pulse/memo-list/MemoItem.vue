<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { useAttachmentsStore, useTagsStore } from '@/stores';
import { ref, watch } from 'vue';
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

const tagsStore = useTagsStore();
const attachmentsStore = useAttachmentsStore(String(props.memo.id));

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
    console.log('submit');
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
        @uploaded="attachmentsStore.onUploaded"
        @attachmentUp="attachmentsStore.onAttachmentUp"
        @attachmentDown="attachmentsStore.onAttachmentDown"
        @attachmentRemove="attachmentsStore.removeAttachment"
        @extractedTags="handleExtractedTags"
        @submit="handleSubmit"
        @cancel="handleCancel"
    />
    <Card class="gap-2" v-else>
        <CardHeader>
            <MemoHeader :memo="props.memo" />
        </CardHeader>
        <CardContent>
            <MemoBody :memo="props.memo" @edit="handleEdit" />
        </CardContent>
    </Card>
</template>
