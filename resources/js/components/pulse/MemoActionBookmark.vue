<script setup lang="ts">
import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { Bookmark, Loader } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const isLoading = ref(false);

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
    <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
        <Loader :size="16" class="animate-spin" v-if="isLoading" />
        <Bookmark :size="16" :fill="bookmarkIconBg" @click="handleBookmark" class="cursor-pointer" v-else />
    </div>
</template>
