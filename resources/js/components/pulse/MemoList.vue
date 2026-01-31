<script setup lang="ts">
import MemoItem from './MemoItem.vue';
import Pagination from './Pagination.vue';

const props = defineProps<{
    memos: PulseMemoIndexResponse['data'];
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-2">
        <MemoItem :memo="memo" v-for="memo in props.memos" />
        <Pagination />
    </div>
</template>
