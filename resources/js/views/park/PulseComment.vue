<script setup lang="ts">
import api from '@/api';
import { Card, CardContent } from '@/components/base/card';
import { CommentInput, CommentList, MemoItem } from '@/components/pulse';
import useCommentsStore from '@/stores/comments';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const isMemoLoading = ref(true);
const memo = ref<PulseMemoStoreResponse['data']>();

const route = useRoute();
const commentsStore = useCommentsStore();

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

const handleLoadMore = () => {
    const currentPage = commentsStore.comments?.meta.current_page ?? 1;

    commentsStore.fetchList(memoId, currentPage + 1);
};

const handleCommentPosted = (comment: PulseCommentStoreResponse['data']) => {
    commentsStore.append(comment);

    if (memo.value) {
        memo.value.comments_count++;
    }
};

onMounted(() => {
    fetchMemo();

    commentsStore.fetchList(memoId);
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
            <CommentList :comments="commentsStore.comments" @load-more="handleLoadMore" />
            <CommentInput :memo-id="memoId" @posted="handleCommentPosted" />
        </template>
    </div>
</template>
