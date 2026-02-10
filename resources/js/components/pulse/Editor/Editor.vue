<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import { useTextareaAutosize } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
import { ActionButton } from './action-button';
import { AttachmentList } from './attachment';
import TagList from './tags/TagList.vue';
import TagsSuggestion from './tags/TagsSuggestion.vue';
import { Attachment, SubmitData, TagOrderedList } from './types';

// experimental: for Parent.v-model
const modelValue = defineModel({
    type: String,
    default: '',
});

const emit = defineEmits(['update:modelValue']);

const props = defineProps<{
    isLoading: boolean;
    enableCancel: boolean;
    tags: TagOrderedList;
    attachments: Attachment[];
    onUploaded: (files: Attachment[]) => void;
    onAttachmentUp: (index: number) => void;
    onAttachmentDown: (index: number) => void;
    onAttachmentRemove: (index: number) => void;
    onExtractedTags: (tags: string[]) => void;
    onSubmit: (data: SubmitData) => void;
    onCancel?: () => void;
}>();

const { textarea: editorRef, input: editor, triggerResize: updateEditorHeight } = useTextareaAutosize();

const tags = computed(() => props.tags);
const attachments = computed(() => props.attachments);
const extractedTags = ref<string[]>([]);

const handleSubmit = () => {
    props.onSubmit({
        // model: Self.editor
        editor: editor,
        content: editor.value,
        attachments: attachments.value,
        tags: extractedTags.value,
    });
};

const handleTextareaInput = (e: any) => {
    // model: Parent.v-model
    emit('update:modelValue', e.target.value);
};

const handleExtractedTags = (tags: string[]) => {
    extractedTags.value = tags.map((tag: string) => {
        return tag.slice(1);
    });

    props.onExtractedTags(extractedTags.value);
};

watch(
    () => modelValue.value,
    (newModelValue) => {
        editor.value = newModelValue;
    },
);

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
                        @input="handleTextareaInput($event)"
                    >
                    </textarea>
                    <TagsSuggestion :editor-ref="editorRef" :editor-methods="editorMethods" :tag-list="tags" />
                </div>
                <div class="flex w-full flex-col gap-2">
                    <TagList :editor="editor" @extracted="handleExtractedTags" />
                    <AttachmentList
                        :attachments="attachments"
                        @up="props.onAttachmentUp"
                        @down="props.onAttachmentDown"
                        @remove="props.onAttachmentRemove"
                    />
                </div>
                <div class="flex gap-2">
                    <ActionButton
                        :isLoading="isLoading"
                        :enableCancel="props.enableCancel"
                        @uploaded="props.onUploaded"
                        @submit="handleSubmit"
                        @cancel="props.onCancel?.()"
                    />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
