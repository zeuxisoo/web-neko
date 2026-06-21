<script setup lang="ts">
import api from '@/api';
import { PulseCommentStorePayload, PulseCommentStoreResponse } from '@/api/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import useUserStore from '@/stores/user';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { useTextareaAutosize } from '@vueuse/core';
import { Loader } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    memoId: number;
}>();

const emit = defineEmits<{
    posted: [comment: PulseCommentStoreResponse['data']];
}>();

const userStore = useUserStore();
const { textarea: commentRef, input: commentInput } = useTextareaAutosize();

const isLoading = ref(false);

const handlePost = async () => {
    if (!commentInput.value.trim()) {
        return;
    }

    isLoading.value = true;

    try {
        const formData = validator.form('pulse.comment.store').validate({
            memo_id: props.memoId,
            content: commentInput.value.trim(),
        });

        const { data, error } = await api.pulse.comment.store(formData as PulseCommentStorePayload).json<PulseCommentStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data.value?.ok) {
            const comment = data.value.data;

            emit('posted', comment);

            commentInput.value = '';

            toast.info('Comment posted');
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle comment post action');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Card>
        <CardContent>
            <div class="flex flex-col gap-2">
                <div class="grid grid-cols-1 gap-2 md:grid-cols-[auto_1fr]">
                    <Avatar class="hidden h-10 w-10 rounded-lg md:flex">
                        <AvatarImage v-if="userStore.link_cover" :src="userStore.link_cover" :alt="userStore.username" />
                        <AvatarFallback class="rounded-lg">{{ userStore.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
                    </Avatar>
                    <textarea
                        ref="commentRef"
                        v-model="commentInput"
                        rows="1"
                        class="flex min-h-10 w-full resize-none rounded-md border border-input bg-transparent p-2 text-sm outline-none placeholder:opacity-60"
                        placeholder="Write a comment..."
                    />
                </div>
                <div class="flex justify-end">
                    <Button size="sm" :disabled="isLoading" @click="handlePost">
                        <Loader v-if="isLoading" class="animate-spin" />
                        <span v-else>Post</span>
                    </Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
