<script setup lang="ts">
import { useAlertDialog } from '@/components/alert-dialog';
import { XIcon } from 'lucide-vue-next';
import { Link } from '../types';

const props = defineProps<{
    links: Link[];
}>();

const emit = defineEmits<{
    remove: [index: number];
}>();
console.log(props.links);
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
        <div class="h-8 w-8 shrink-0 overflow-hidden rounded-sm">
            <img :src="link.image" class="h-full w-full object-cover" />
        </div>
        <div class="flex flex-1 flex-col gap-1">
            <span class="truncate font-medium">{{ link.title }}</span>
            <span class="truncate text-muted-foreground">{{ link.url }}</span>
        </div>
        <div className="flex items-center gap-1.5">
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="remove">
                <XIcon class="text-muted-foreground hover:text-destructive" :size="14" @click="handleDelete(link, index)" />
            </button>
        </div>
    </div>
</template>
