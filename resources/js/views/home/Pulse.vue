<script setup lang="ts">
import { Editor, MessageList, Pagination } from '@/components/pulse';
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor />
        <MessageList />
        <Pagination />
    </div>
</template>
