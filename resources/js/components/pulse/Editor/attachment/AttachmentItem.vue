<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/base/alert-dialog';
import { fileSubType, humanSize } from '@/utils';
import { ChevronDownIcon, ChevronUpIcon, XIcon } from 'lucide-vue-next';
import { Attachment } from '../types';

const props = defineProps<{
    attachments: Attachment[];
    onUp?: (index: number) => void;
    onDown?: (index: number) => void;
    onRemove?: (index: number) => void;
}>();
</script>

<template>
    <div class="flex items-center gap-1 px-0.5 py-1 transition-all hover:bg-accent/50" v-for="(attachment, index) in props.attachments" :key="index">
        <div class="h-6 w-6 shrink-0 overflow-hidden rounded-sm">
            <img :src="attachment.links.cover" class="h-full w-full object-cover" />
        </div>
        <div class="flex flex-1 flex-col gap-1.5 text-xs md:flex-row">
            <span class="truncate">{{ attachment.original_name }}</span>
            <div class="flex shrink-0 text-muted-foreground">
                <span class="hidden md:block">{{ fileSubType(attachment.type) }}</span>
                <span class="hidden md:block">&nbsp;•&nbsp;</span>
                <span class="hidden md:block">{{ humanSize(attachment.size) }}</span>
            </div>
        </div>
        <div className="flex items-center gap-1.5">
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="Up" @click="props.onUp(index)" v-if="props.onUp">
                <ChevronUpIcon class="text-muted-foreground" :size="14" />
            </button>
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="Down" @click="props.onDown(index)" v-if="props.onDown">
                <ChevronDownIcon class="text-muted-foreground" :size="14" />
            </button>
            <AlertDialog v-if="props.onRemove">
                <AlertDialogTrigger>
                    <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="remove">
                        <XIcon class="text-muted-foreground hover:text-destructive" :size="14" />
                    </button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Note!</AlertDialogTitle>
                        <AlertDialogDescription>
                            Are you sure delete the attachment: <span class="text-primary">{{ attachment.original_name }}</span> ?<br /><br />
                            Note: This action cannot be undone. This will permanently remove this attachment and delete record from our servers.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Cannel</AlertDialogCancel>
                        <AlertDialogAction @click="props.onRemove(index)">Yes</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </div>
</template>
