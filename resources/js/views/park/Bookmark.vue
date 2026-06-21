<script setup lang="ts">
import api from '@/api';
import { PulseBookmarkIndexResponse, PulseBookmarkRemoveResponse } from '@/api/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { EmptyState, Header } from '@/components/page';
import { WhoopsHandler } from '@/utils';
import { Bookmark, ChevronLeft, ChevronRight, Loader } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { toast } from 'vue-sonner';

const router = useRouter();
const route = useRoute();

const isLoading = ref(true);
const removingMemoIds = ref<number[]>([]);
const bookmarks = ref<PulseBookmarkIndexResponse>();

const fetchBookmarks = async () => {
    isLoading.value = true;

    try {
        const page = Number(router.currentRoute.value.query.page) || 1;

        const { data, error } = await api.pulse.bookmark.index({ page }).json<PulseBookmarkIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            bookmarks.value = data.value;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch bookmark list action in bookmark page');
    } finally {
        isLoading.value = false;
    }
};

const handlePrev = () => {
    if (!bookmarks.value?.links.prev || !bookmarks.value?.meta) return;

    const prevPage = (bookmarks.value.meta.current_page ?? 1) - 1;

    router.push({
        name: 'park.bookmark',
        query: {
            ...router.currentRoute.value.query,
            page: prevPage,
        },
    });
};

const handleNext = () => {
    if (!bookmarks.value?.links.next || !bookmarks.value?.meta) return;

    const nextPage = (bookmarks.value.meta.current_page ?? 1) + 1;

    router.push({
        name: 'park.bookmark',
        query: {
            ...router.currentRoute.value.query,
            page: nextPage,
        },
    });
};

const handleRemoveBookmark = async (memoId: number) => {
    try {
        removingMemoIds.value.push(memoId);

        const { data, error } = await api.pulse.bookmark.remove(memoId).json<PulseBookmarkRemoveResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;

            // refresh the list
            fetchBookmarks();

            toast.info(result.message);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when remove bookmark action in bookmark page');
    } finally {
        removingMemoIds.value = removingMemoIds.value.filter((id) => id !== memoId);
    }
};

watch(
    () => route.query.page,
    () => {
        fetchBookmarks();
    },
    { immediate: true },
);
</script>

<template>
    <div class="bookmark grid gap-3">
        <template v-if="bookmarks">
            <Header #main>
                <h1 class="text-2xl font-semibold tracking-tight">Bookmarks</h1>
                <span
                    v-if="bookmarks.data.length > 0"
                    class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary"
                >
                    {{ bookmarks.data.length }}
                </span>
            </Header>

            <template v-if="bookmarks.data.length > 0">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="memo in bookmarks.data" :key="memo.id" class="relative p-4">
                        <CardContent class="p-0">
                            <RouterLink :to="{ name: 'park.pulse.comment', params: { id: memo.id } }">
                                <p class="line-clamp-3 text-sm">{{ memo.content }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <Avatar class="h-5 w-5 rounded-md bg-accent">
                                        <AvatarImage :src="memo.user.link_cover" :alt="memo.user.username" />
                                        <AvatarFallback class="rounded-md text-sm">{{ memo.user.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
                                    </Avatar>
                                    <p class="text-sm text-muted-foreground">{{ memo.user.username }}</p>
                                </div>
                            </RouterLink>
                            <Button
                                variant="ghost"
                                class="absolute top-0 right-0 h-6 w-6"
                                :disabled="removingMemoIds.includes(memo.id)"
                                @click="handleRemoveBookmark(memo.id)"
                            >
                                <Loader v-if="removingMemoIds.includes(memo.id)" class="h-full w-full animate-spin" />
                                <Bookmark v-else fill="currentColor" :size="14" />
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <Card class="py-3">
                    <CardContent>
                        <div class="flex w-full justify-between">
                            <Button class="cursor-pointer" :disabled="!bookmarks.links.prev" @click="handlePrev"> <ChevronLeft />Prev </Button>
                            <Button class="cursor-pointer" :disabled="!bookmarks.links.next" @click="handleNext"> Next<ChevronRight /> </Button>
                        </div>
                    </CardContent>
                </Card>
            </template>
        </template>

        <EmptyState :is-loading="isLoading" message="No bookmarks yet" v-if="!bookmarks || bookmarks.data.length <= 0" />
    </div>
</template>
