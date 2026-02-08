<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { humanDateTime } from '@/utils';
import { ref } from 'vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const showRawDateTime = ref(false);
</script>

<template>
    <div class="flex items-center gap-2 text-left text-sm">
        <Avatar class="h-12 w-12 rounded-lg">
            <AvatarImage :src="props.memo.user.link" />
            <AvatarFallback class="rounded-lg"> AV </AvatarFallback>
        </Avatar>
        <div class="grid flex-1 text-left text-sm leading-6">
            <span class="truncate font-semibold">{{ props.memo.user.username }}</span>
            <span class="flex items-center text-xs text-accent-foreground/80" @click="showRawDateTime = !showRawDateTime">
                {{ humanDateTime(props.memo.created_at, showRawDateTime) }}
            </span>
        </div>
    </div>
</template>
