<script setup lang="ts">
import Badge from '@/components/base/badge/Badge.vue';
import { MessageSquareMore } from 'lucide-vue-next';
import { RouterLink } from 'vue-router';
import MemoActionBookmark from '../memo-body/MemoActionBookmark.vue';
import MemoActionMore from '../memo-body/MemoActionMore.vue';
import MemoAttachment from '../memo-body/MemoAttachment.vue';
import MemoContent from '../memo-body/MemoContent.vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();
</script>

<template>
    <div class="flex flex-col gap-2">
        <MemoContent :content="props.memo.content" />
        <MemoAttachment :attachments="props.memo.attachments" />
        <div class="gap-2">
            <Badge variant="secondary" v-for="(tag, i) in props.memo.tags" :key="i"> #{{ tag.name }} </Badge>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="comment">
                <RouterLink
                    :to="{ name: 'park.pulse.comment', params: { id: props.memo.id } }"
                    class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium"
                >
                    <MessageSquareMore :size="16" /> 0
                </RouterLink>
            </div>
            <div class="bookmark flex justify-center">
                <MemoActionBookmark :memo="props.memo" />
            </div>
            <div class="action flex justify-end">
                <MemoActionMore :memo="props.memo" />
            </div>
        </div>
    </div>
</template>
