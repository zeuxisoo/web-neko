<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { useMemoStore, useTagStore } from '@/stores';
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

const tagsStore = useTagStore();
const memoStore = useMemoStore(props.memo.id);

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
            memoStore.setMemo(props.memo);
            memoStore.setAttachments(props.memo.attachments);
        }
    },
);
</script>

<template>
    <Editor
        v-model="editor"
        class="border-2 border-accent-foreground/30"
        v-if="isEditing"
        :isLoading="isLoading"
        :enableCancel="true"
        :tags="tagsStore.tags"
        :attachments="memoStore.attachmentsStore.attachments"
        @uploaded="memoStore.attachmentsStore.onUploaded"
        @attachmentUp="memoStore.attachmentsStore.onAttachmentUp"
        @attachmentDown="memoStore.attachmentsStore.onAttachmentDown"
        @attachmentRemove="memoStore.attachmentsStore.removeAttachment"
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
