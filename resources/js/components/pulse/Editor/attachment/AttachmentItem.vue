<script setup lang="ts">
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
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="Up" @click="onUp(index)" v-if="onUp">
                <ChevronUpIcon class="text-muted-foreground" :size="14" />
            </button>
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="Down" @click="onDown(index)" v-if="onDown">
                <ChevronDownIcon class="text-muted-foreground" :size="14" />
            </button>
            <button class="rouned-sm text-xs transition-colors hover:bg-accent" title="remove" @click="onRemove(index)" v-if="onRemove">
                <XIcon class="text-muted-foreground hover:text-destructive" :size="14" />
            </button>
        </div>
    </div>
</template>
