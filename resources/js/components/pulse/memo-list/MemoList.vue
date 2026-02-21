<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/base/alert';
import { Card, CardContent } from '@/components/base/card';
import { Info } from 'lucide-vue-next';
import MemoFilter from './MemoFilter.vue';
import MemoItem from './MemoItem.vue';
import MemoPagination from './MemoPagination.vue';

const props = defineProps<{
    memos: PulseMemoIndexResponse | null;
}>();
</script>

<template>
    <div class="grid grid-cols-1">
        <MemoFilter />
    </div>
    <div class="grid grid-cols-1 gap-2" v-if="props.memos">
        <MemoItem :memo="memo" v-for="memo in props.memos.data" :key="memo.id" />
        <MemoPagination :links="props.memos.links" :meta="props.memos.meta" v-if="props.memos.data.length > 0" />
    </div>
    <div class="grid grid-cols-1 gap-2" v-else>
        <Card>
            <CardContent>
                <Alert>
                    <Info />
                    <AlertTitle>Oops!</AlertTitle>
                    <AlertDescription>Do you want to create first memo?</AlertDescription>
                </Alert>
            </CardContent>
        </Card>
    </div>
</template>
