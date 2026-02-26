<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { useAlertDialog } from '@/components/alert-dialog';
import { LinkIcon, XIcon } from 'lucide-vue-next';
import { Link } from '../types';

const props = defineProps<{
    links: Link[];
}>();

const emit = defineEmits<{
    remove: [index: number];
}>();

const alertDialog = useAlertDialog();

const handleDelete = async (link: Link, index: number) => {
    const dialogResult = await alertDialog.start({
        title: `Delete ${link.title} ?`,
        description: 'Note: This action cannot be undone. This will permanently remove this link and delete record from our servers.',
    });

    if (dialogResult === 'ok') {
        emit('remove', index);
    }
};
</script>

<template>
    <div class="flex items-center gap-2 px-0.5 py-1 text-xs transition-all hover:bg-accent/50" v-for="(link, index) in props.links" :key="index">
        <Avatar class="h-8 w-8 shrink-0 rounded-sm">
            <AvatarImage v-if="link.image" :src="link.image" :alt="link.title" />
            <AvatarFallback class="rounded-sm">
                <LinkIcon :size="16" />
            </AvatarFallback>
        </Avatar>
        <div class="flex w-0 flex-1 flex-col gap-1">
            <span class="truncate overflow-hidden font-medium">{{ link.title }}</span>
            <span class="truncate overflow-hidden text-muted-foreground">{{ link.url }}</span>
        </div>
        <div className="flex items-center gap-1.5">
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="remove">
                <XIcon class="text-muted-foreground hover:text-destructive" :size="14" @click="handleDelete(link, index)" />
            </button>
        </div>
    </div>
</template>
