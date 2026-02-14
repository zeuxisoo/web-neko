<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import MemoContent from '@/components/pulse/memo-list/memo-body/MemoContent.vue';
import MemoHeader from '@/components/pulse/memo-list/memo/MemoHeader.vue';

const props = defineProps<{
    comment: PulseCommentStoreResponse['data'];
}>();
</script>

<template>
    <Card>
        <CardContent>
            <div class="flex flex-col gap-2">
                <MemoHeader :item="props.comment" />
                <div class="pl-12">
                    <MemoContent :content="props.comment.content" />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
