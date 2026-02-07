<script setup lang="ts">
import api from '@/api';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Card, CardHeader } from '@/components/base/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { humanDateTime, WhoopsHandler } from '@/utils';
import { Bookmark, Ellipsis, Loader, MessageSquareMore, PaperclipIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import MemoContent from './MemoContent.vue';
import PreviewImage from './PreviewImage.vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const isLoading = ref(false);
const showRawDateTime = ref(false);

// fill icon bg to default `currentColor` when memo bookmarked
const bookmarkIconBg = computed(() => {
    return props.memo.is_bookmarked ? 'currentColor' : 'none';
});

const handleBookmark = () => {
    if (!props.memo.is_bookmarked) {
        addBookmark();
    } else {
        removeBookmark();
    }
};

const addBookmark = async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.bookmark.add(props.memo.id).json<PulseBookmarkAddResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;

            props.memo.is_bookmarked = true;

            toast.info(result.message);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle memo bookmark add action');
    } finally {
        isLoading.value = false;
    }
};

const removeBookmark = async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.bookmark.remove(props.memo.id).json<PulseBookmarkRemoveResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;

            props.memo.is_bookmarked = false;

            toast.info(result.message);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle memo bookmark remove action');
    } finally {
        isLoading.value = false;
    }
};
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
                <MemoContent :content="props.memo.content" />
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
                        <RouterLink
                            :to="{ name: 'park.pulse.comment', params: { id: props.memo.id } }"
                            class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium"
                        >
                            <MessageSquareMore :size="16" /> 0
                        </RouterLink>
                    </div>
                    <div class="bookmark flex justify-center">
                        <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
                            <Loader :size="16" class="animate-spin" v-if="isLoading" />
                            <Bookmark :size="16" :fill="bookmarkIconBg" @click="handleBookmark" class="cursor-pointer" v-else />
                        </div>
                    </div>
                    <div class="action flex justify-end">
                        <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Ellipsis :size="16" />
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem>Edit</DropdownMenuItem>
                                    <DropdownMenuItem>Delete</DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>
                </div>
            </div>
        </CardHeader>
    </Card>
</template>
