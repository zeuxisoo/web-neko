<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import { useTextareaAutosize } from '@vueuse/core';
import { computed, ref } from 'vue';
import { ActionButton } from './action-button';
import { AttachmentList } from './attachment';
import TagsSuggestion from './TagsSuggestion.vue';
import { Attachment, SubmitData, TagList } from './types';

const props = defineProps<{
    tagList: TagList;
    onSubmit: (data: SubmitData) => void;
}>();

const { textarea: editorRef, input: editor, triggerResize: updateEditorHeight } = useTextareaAutosize();

const tagList = computed(() => props.tagList);
const attachments = ref<Attachment[]>([]);

const handleUploaded = (files: Attachment[]) => {
    attachments.value = attachments.value.concat(files);
};

const handleAttachmentUp = (index: number) => {
    if (index <= 0) return;

    const item = attachments.value[index];

    attachments.value[index] = attachments.value[index - 1];
    attachments.value[index - 1] = item;
};

const handleAttachmentDown = (index: number) => {
    if (index >= attachments.value.length - 1) return;

    const item = attachments.value[index];

    attachments.value[index] = attachments.value[index + 1];
    attachments.value[index + 1] = item;
};

const handleAttachmentRemove = (index: number) => {
    // TODO: remove remote file via api (need impl)
    attachments.value.splice(index, 1);
};

const handleSubmit = () => {
    props.onSubmit({
        editor: editor.value,
        attachments: attachments.value,
    });
};

const editorMethods = {
    removeText: (start: number, length: number) => {
        if (!editorRef.value) {
            return;
        }

        // capture exists content without `trigger key+start char`
        const oldValue = editorRef.value.value;
        const value = oldValue.slice(0, start) + oldValue.slice(start + length);

        editorRef.value.value = value;
        editorRef.value.focus();
        editorRef.value.selectionEnd = start;

        editorRef.value.dispatchEvent(new Event('input'));

        updateEditorHeight();
    },

    insertText: (content: string = '', prefix: string = '', suffix: string = '') => {
        if (!editorRef.value) {
            return;
        }

        const cursorPosition = editorRef.value.selectionStart;
        const endPosition = editorRef.value.selectionEnd;
        const oldValue = editorRef.value.value;

        const value =
            oldValue.slice(0, cursorPosition) +
            prefix +
            (content || oldValue.slice(cursorPosition, endPosition)) +
            suffix +
            oldValue.slice(endPosition);

        editorRef.value.value = value;
        editorRef.value.focus();
        editorRef.value.selectionEnd = endPosition + prefix.length + content.length;

        editorRef.value.dispatchEvent(new Event('input'));

        updateEditorHeight();
    },
};
</script>

<template>
    <Card>
        <CardContent>
            <div class="item-center grid w-full gap-2">
                <div class="relative flex flex-col">
                    <textarea
                        v-model="editor"
                        ref="editorRef"
                        rows="1"
                        name="editor"
                        class="w-full resize-none rounded-md border-0 bg-transparent p-0.5 text-base outline-none placeholder:opacity-60"
                        placeholder="Place whatever you want"
                        autofocus
                    >
                    </textarea>
                    <TagsSuggestion :editor-ref="editorRef" :editor-methods="editorMethods" :tag-list="tagList" />
                </div>
                <div class="flex w-full flex-col gap-2">
                    <AttachmentList
                        :attachments="attachments"
                        @up="handleAttachmentUp"
                        @down="handleAttachmentDown"
                        @remove="handleAttachmentRemove"
                    />
                </div>
                <div class="flex gap-2">
                    <ActionButton @uploaded="handleUploaded" @submit="handleSubmit" />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
