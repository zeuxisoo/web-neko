<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Textarea } from '@/components/base/textarea';
import { ImageDialog } from '@/components/upload-dialog';
import { SendHorizontal } from 'lucide-vue-next';
import { ref, useTemplateRef } from 'vue';

const editorRef = useTemplateRef<HTMLTextAreaElement>('editor-ref');
const editor = ref('');

const updateEditorHeight = () => {
    // add 4px to match `p-1` style when user input
    if (editorRef.value && editorRef.value.style) {
        console.log(editorRef.value);
        editorRef.value.style.height = 'auto';
        editorRef.value.style.height = (editorRef.value.scrollHeight ?? 0) + 4 + 'px';
    }
};

const handleEditorInput = (_: InputEvent) => {
    updateEditorHeight();
};

const handleSubmit = () => {
    console.log(editor.value);
};
</script>

<template>
    <Card>
        <CardContent>
            <div class="item-center grid w-full gap-4">
                <div class="flex flex-col">
                    <Textarea
                        v-model="editor"
                        ref="editor-ref"
                        rows="1"
                        name="editor"
                        class="w-full resize-none rounded-md border-2 p-1"
                        placeholder="Place whatever you want"
                        @input="handleEditorInput($event)"
                        autofocus
                    >
                    </Textarea>
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <ImageDialog />
                    </div>
                    <Button @click="handleSubmit"> <SendHorizontal /> Submit </Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
