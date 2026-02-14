<script setup lang="ts">
import api from '@/api';
import { Card, CardContent } from '@/components/base/card';
import { CommentInput, CommentList, MemoItem } from '@/components/pulse';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const memo = ref<PulseMemoStoreResponse['data']>();
const comments = ref<PulseCommentIndexResponse>();
const isMemoLoading = ref(true);
const isCommentsLoading = ref(true);

const route = useRoute();
const memoId = Number(route.params.id);

const fetchMemo = async () => {
    try {
        isMemoLoading.value = true;

        const { data, error } = await api.pulse.memo.show(memoId).json<PulseMemoShowResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            memo.value = data.value.data;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch memo action in pulse comment page');
    } finally {
        isMemoLoading.value = false;
    }
};

const fetchComments = async (page: number = 1) => {
    try {
        isCommentsLoading.value = true;

        const { data, error } = await api.pulse.comment.index({ memo_id: memoId, page }).json<PulseCommentIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            if (page === 1) {
                comments.value = data.value;
            } else {
                // append new comments to existing list for pagination
                comments.value = {
                    ...data.value,
                    data: [...(comments.value?.data ?? []), ...data.value.data],
                };
            }
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch comments action in pulse comment page');
    } finally {
        isCommentsLoading.value = false;
    }
};

const handleLoadMore = () => {
    const currentPage = comments.value?.meta?.current_page ?? 1;

    fetchComments(currentPage + 1);
};

onMounted(() => {
    fetchMemo();
    fetchComments();
});
</script>

<template>
    <div class="pulse grid gap-3">
        <Card v-if="isMemoLoading">
            <CardContent>
                <div class="flex items-center justify-center py-12">
                    <span class="text-sm text-accent-foreground/60">Loading...</span>
                </div>
            </CardContent>
        </Card>

        <template v-else-if="memo">
            <MemoItem :memo="memo" />
            <CommentList :comments="comments" @load-more="handleLoadMore" />
            <CommentInput :memo-id="memoId" />
        </template>
    </div>
</template>
