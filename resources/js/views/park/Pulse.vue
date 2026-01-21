<script setup lang="ts">
import { Editor, MessageList, Pagination } from '@/components/pulse';
import { TagList } from '@/components/pulse/Editor/types';
import { ref } from 'vue';

const tagList = ref<TagList>({
    Apple: 1,
    Banana: 5,
    Carrot: 3,
    Donut: 7,
});
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor :tag-list="tagList" />
        <MessageList />
        <Pagination />
    </div>
</template>
