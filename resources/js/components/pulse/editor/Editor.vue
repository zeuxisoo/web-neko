<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import { useAttachmentsStore, useSettingsStore } from '@/stores';
import { useDropZone, useTextareaAutosize } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
import { ActionButton } from './action-button';
import { AttachmentList } from './attachment';
import { LinkList } from './link';
import TagList from './tags/TagList.vue';
import TagsSuggestion from './tags/TagsSuggestion.vue';
import { Attachment, Link, TagOrderedList } from './types';

// experimental: for Parent.v-model
const modelValue = defineModel({
    type: String,
    default: '',
});

const emit = defineEmits([
    'update:modelValue',
    'uploaded',
    'attachmentUp',
    'attachmentDown',
    'attachmentRemove',
    'linked',
    'linkRemove',
    'extractedTags',
    'submit',
    'cancel',
]);

const props = defineProps<{
    isLoading: boolean;
    enableCancel: boolean;
    tags: TagOrderedList;
    attachments: Attachment[];
    links: Link[];
    memo?: PulseMemoIndexResponse['data'][number];
}>();

const tags = computed(() => props.tags);
const attachments = computed(() => props.attachments);
const links = computed(() => props.links);
const extractedTags = ref<string[]>([]);
const dropZoneRef = ref<HTMLElement>();

const settingsStore = useSettingsStore();
const attachmentsStore = useAttachmentsStore();

const { textarea: editorRef, input: editor, triggerResize: updateEditorHeight } = useTextareaAutosize();
const { isOverDropZone } = useDropZone(dropZoneRef, {
    onDrop: async (files: File[] | null) => {
        if (!files || files.length === 0 || attachmentsStore.isUploading) {
            return;
        }

        const maxFileSize = (settingsStore.attachment?.max_size_kb ?? 0) * 1024;
        const allowedTypes = (settingsStore.attachment?.allowed_mimes ?? []).map((ext: string) => {
            return ext === 'jpg' ? 'image/jpeg' : `image/${ext}`;
        });

        const uploadedAttachments = await attachmentsStore.uploadFiles(files, {
            maxFileSize,
            allowedTypes,
        });

        if (uploadedAttachments.length > 0) {
            emit('uploaded', uploadedAttachments);
        }
    },
});

const handleSubmit = () => {
    emit('submit', {
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

    emit('extractedTags', extractedTags.value);
};

watch(
    () => modelValue.value,
    (newModelValue) => {
        editor.value = newModelValue;
    },
    { immediate: true },
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
    <Card class="py-4">
        <CardContent class="px-4">
            <div
                ref="dropZoneRef"
                class="item-center grid w-full gap-2 p-2"
                :class="{ 'rounded-md border border-dashed border-primary bg-muted/50': isOverDropZone }"
            >
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
                        @up="emit('attachmentUp', $event)"
                        @down="emit('attachmentDown', $event)"
                        @remove="emit('attachmentRemove', $event)"
                    />
                    <LinkList :links="links" @remove="emit('linkRemove', $event)" />
                </div>
                <div class="flex gap-2">
                    <ActionButton
                        :isLoading="isLoading"
                        :enableCancel="props.enableCancel"
                        :memo="props.memo"
                        @uploaded="emit('uploaded', $event)"
                        @linked="emit('linked', $event)"
                        @submit="handleSubmit"
                        @cancel="emit('cancel')"
                    />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
