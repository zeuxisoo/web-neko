<script setup lang="ts">
import { Card, CardContent } from '@/components/base/card';
import CommentItem from './CommentItem.vue';
import CommentPagination from './CommentPagination.vue';

const props = defineProps<{
    comments: PulseCommentIndexResponse | null;
    onLoadMore: () => void;
}>();
</script>

<template>
    <div class="flex flex-col gap-2" v-if="props.comments">
        <Card v-if="props.comments.data.length <= 0">
            <CardContent>
                <h3 class="text-lg font-semibold">Comments</h3>
                <p class="text-sm text-accent-foreground/60">No comments yet</p>
            </CardContent>
        </Card>
        <template v-else>
            <CommentItem v-for="comment in props.comments.data" :key="comment.id" :comment="comment" />
            <CommentPagination :links="props.comments.links" :meta="props.comments.meta" @load-more="props.onLoadMore" />
        </template>
    </div>
</template>
