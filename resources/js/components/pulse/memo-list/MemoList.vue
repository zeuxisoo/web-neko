<script setup lang="ts">
import { EmptyState } from '@/components/page';
import MemoFilter from './MemoFilter.vue';
import MemoItem from './MemoItem.vue';
import MemoPagination from './MemoPagination.vue';

const props = defineProps<{
    isLoading: boolean;
    memos: PulseMemoIndexResponse | null;
}>();
</script>

<template>
    <MemoFilter />
    <div class="grid grid-cols-1 gap-3">
        <template v-if="props.memos">
            <MemoItem :memo="memo" v-for="memo in props.memos.data" :key="memo.id" />
            <MemoPagination :links="props.memos.links" :meta="props.memos.meta" v-if="props.memos.data.length > 0" />
        </template>

        <EmptyState :is-loading="props.isLoading" message="No memos yet" v-if="!props.memos || props.memos.data.length <= 0" />
    </div>
</template>
