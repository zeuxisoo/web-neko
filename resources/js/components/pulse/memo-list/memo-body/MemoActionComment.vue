<script setup lang="ts">
import { MessageSquareMore } from 'lucide-vue-next';
import { RouterLink } from 'vue-router';

const props = withDefaults(
    defineProps<{
        memo: PulseMemoIndexResponse['data'][number];
        enableActionComment?: boolean;
    }>(),
    {
        enableActionComment: true,
    },
);
</script>

<template>
    <div class="comment">
        <RouterLink
            v-if="props.enableActionComment"
            :to="{ name: 'park.pulse.comment', params: { id: props.memo.id } }"
            class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium"
        >
            <MessageSquareMore :size="16" /> {{ props.memo.comments_count }}
        </RouterLink>
        <span v-else class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
            <MessageSquareMore :size="16" /> {{ props.memo.comments_count }}
        </span>
    </div>
</template>
