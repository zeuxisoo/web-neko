<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import { computed, ref, useTemplateRef } from 'vue';
import { ActionButton } from './action-button';
import TagsSuggestion from './TagsSuggestion.vue';
import { Attachment, SubmitData, TagList } from './types';

const props = defineProps<{
    tagList: TagList;
    onSubmit: (data: SubmitData) => void;
}>();

const editorRef = useTemplateRef<HTMLTextAreaElement | null>('editor-ref');
const editor = ref('');
const tagList = computed(() => props.tagList);

const updateEditorHeight = () => {
    // add 4px to match `p-1` style when user input
    if (editorRef.value && editorRef.value.style) {
        editorRef.value.style.height = 'auto';
        editorRef.value.style.height = (editorRef.value.scrollHeight ?? 0) + 4 + 'px';
    }
};

const handleEditorInput = (_: InputEvent) => {
    updateEditorHeight();
};

const handleUploaded = (attachments: Attachment[]) => {
    console.log(attachments);
};

const handleSubmit = () => {
    props.onSubmit({
        editor: editor.value,
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
            <div class="item-center grid w-full gap-4">
                <div class="relative flex flex-col">
                    <textarea
                        v-model="editor"
                        ref="editor-ref"
                        rows="1"
                        name="editor"
                        class="w-full resize-none rounded-md border-2 bg-transparent p-2 text-base placeholder:opacity-60"
                        placeholder="Place whatever you want"
                        @input="handleEditorInput($event)"
                        autofocus
                    >
                    </textarea>
                    <TagsSuggestion :editor-ref="editorRef" :editor-methods="editorMethods" :tag-list="tagList" />
                </div>
                <div class="flex gap-2">
                    <!--
                    <div class="flex-1">
                    </div>
                    <Button @click="handleSubmit"> <SendHorizontal /> Submit </Button>
                    -->
                    <ActionButton @uploaded="handleUploaded" @submit="handleSubmit" />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
