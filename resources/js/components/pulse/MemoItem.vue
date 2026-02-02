<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Card, CardHeader } from '@/components/base/card';
import { humanDateTime } from '@/utils';
import { Bookmark, Ellipsis, MessageSquareMore, PaperclipIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import PreviewImage from './PreviewImage.vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const showRawDateTime = ref(false);
</script>
<template>
    <Card>
        <CardHeader>
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
            <div class="flex flex-col gap-2">
                <div class="font-light whitespace-pre-wrap">
                    {{ props.memo.content }}
                </div>
                <div class="boder-boder flex flex-col gap-1 rounded-sm border" v-if="props.memo.attachments.length > 0">
                    <div className="flex items-center gap-1 p-2 border-b border-border bg-muted/30 text-muted-foreground">
                        <PaperclipIcon :size="12" />
                        <span class="text-xs">Attachments ({{ props.memo.attachments.length }})</span>
                    </div>
                    <div class="grid grid-cols-2 justify-items-center gap-2 p-1 md:grid-cols-6">
                        <PreviewImage
                            v-for="(attachment, i) in props.memo.attachments"
                            :key="i"
                            :image="{ src: attachment.links.thumb, title: attachment.original_name }"
                        />
                    </div>
                </div>
                <div class="gap-2">
                    <Badge variant="secondary" v-for="(tag, i) in props.memo.tags" :key="i"> #{{ tag.name }} </Badge>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="comment">
                        <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium"><MessageSquareMore :size="16" /> 0</div>
                    </div>
                    <div class="bookmark flex justify-center">
                        <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
                            <Bookmark :size="16" />
                        </div>
                    </div>
                    <div class="action flex justify-end">
                        <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
                            <Ellipsis :size="16" />
                        </div>
                    </div>
                </div>
            </div>
        </CardHeader>
    </Card>
</template>
