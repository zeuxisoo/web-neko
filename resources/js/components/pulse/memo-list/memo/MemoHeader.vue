<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { humanDateTime } from '@/utils';
import { ref } from 'vue';

const props = defineProps<{
    item: {
        user: MeResponse['data'];
        created_at: string;
    };
}>();

const showRawDateTime = ref(false);
</script>

<template>
    <div class="flex items-center gap-2 text-left text-sm">
        <Avatar class="h-12 w-12 rounded-lg">
            <AvatarImage :src="props.item.user.link" :alt="props.item.user.username" />
            <AvatarFallback class="rounded-lg">{{ props.item.user.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
        </Avatar>
        <div class="grid flex-1 text-left text-sm leading-6">
            <span class="truncate font-semibold">{{ props.item.user.username }}</span>
            <span class="flex items-center text-xs text-accent-foreground/80" @click="showRawDateTime = !showRawDateTime">
                {{ humanDateTime(props.item.created_at, showRawDateTime) }}
            </span>
        </div>
    </div>
</template>
