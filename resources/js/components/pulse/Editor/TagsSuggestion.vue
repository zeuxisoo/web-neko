<script setup lang="ts">
import { EditorMethods } from './types';

const props = defineProps<{
    editorRef: HTMLTextAreaElement | null;
    editorMethods: EditorMethods;
}>();
</script>

<template>
    <div>TODO: Tag Suggest</div>
</template>
