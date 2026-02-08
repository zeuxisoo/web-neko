<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/base/card';
import MemoBody from './memo/MemoBody.vue';
import MemoHeader from './memo/MemoHeader.vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();
</script>

<template>
    <Card class="gap-2">
        <CardHeader>
            <MemoHeader :memo="props.memo" />
        </CardHeader>
        <CardContent>
            <MemoBody :memo="props.memo" />
        </CardContent>
    </Card>
</template>
