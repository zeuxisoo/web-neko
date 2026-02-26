<script setup lang="ts">
import { LinkIcon } from 'lucide-vue-next';

const props = defineProps<{
    links: PulseMemoIndexResponse['data'][number]['links'];
}>();
</script>

<template>
    <div class="flex flex-col gap-1 rounded-sm border" v-if="props.links.length > 0">
        <div class="flex items-center gap-1 border-b border-border bg-muted/30 p-2 text-muted-foreground">
            <LinkIcon :size="12" />
            <span class="text-xs">Links ({{ props.links.length }})</span>
        </div>
        <div class="flex flex-col gap-2 p-1">
            <a
                v-for="(link, i) in props.links"
                :key="i"
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-start gap-2 rounded-sm border border-border p-2 transition-colors hover:bg-muted/50"
            >
                <img v-if="link.image" :src="link.image" :alt="link.title" class="h-12 w-12 shrink-0 rounded-sm object-cover" />
                <div class="flex min-w-0 flex-col gap-1">
                    <span class="truncate text-sm font-medium text-foreground">{{ link.title }}</span>
                    <span class="truncate text-xs text-muted-foreground">{{ link.url }}</span>
                </div>
            </a>
        </div>
    </div>
</template>
