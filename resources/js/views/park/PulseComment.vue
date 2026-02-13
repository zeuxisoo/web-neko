<script setup lang="ts">
import api from '@/api';
import { Card, CardContent } from '@/components/base/card';
import { CommentInput, MemoItem } from '@/components/pulse';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const memo = ref<PulseMemoStoreResponse['data']>();
const isLoading = ref(true);

const route = useRoute();
const memoId = Number(route.params.id);

const fetchMemo = async () => {
    try {
        isLoading.value = true;

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
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchMemo();
});
</script>

<template>
    <div class="pulse grid gap-3">
        <Card v-if="isLoading">
            <CardContent>
                <div class="flex items-center justify-center py-12">
                    <span class="text-sm text-accent-foreground/60">Loading...</span>
                </div>
            </CardContent>
        </Card>

        <template v-else-if="memo">
            <MemoItem :memo="memo" />
            <CommentInput :memo-id="memoId" />
        </template>
    </div>
</template>
