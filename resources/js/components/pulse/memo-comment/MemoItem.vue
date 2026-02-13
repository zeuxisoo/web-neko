<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/base/card';
import MemoBody from '@/components/pulse/memo-list/memo/MemoBody.vue';
import MemoHeader from '@/components/pulse/memo-list/memo/MemoHeader.vue';

defineProps<{
    memo: PulseMemoStoreResponse['data'];
}>();
</script>

<template>
    <Card class="gap-2">
        <CardHeader>
            <MemoHeader :memo="memo" />
        </CardHeader>
        <CardContent>
            <MemoBody :memo="memo" :enable-action-more="false" :enable-action-comment="false" />
        </CardContent>
    </Card>
</template>
