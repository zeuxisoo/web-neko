<script setup lang="ts">
import MemoItem from './MemoItem.vue';
import MemoPagination from './MemoPagination.vue';

const props = defineProps<{
    memos: PulseMemoIndexResponse['data'];
    links: PulseMemoIndexResponse['links'] | undefined;
    meta: PulseMemoIndexResponse['meta'] | undefined;
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-2">
        <MemoItem :memo="memo" v-for="memo in props.memos" />
        <MemoPagination :links="props.links" :meta="props.meta" />
    </div>
</template>
