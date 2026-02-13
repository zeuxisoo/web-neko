<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import useUserStore from '@/stores/user';
import { useTextareaAutosize } from '@vueuse/core';
import { ref } from 'vue';

const userStore = useUserStore();
const { textarea: commentRef, input: commentInput } = useTextareaAutosize();

const isPosting = ref(false);

const handlePost = async () => {
    if (!commentInput.value.trim()) {
        return;
    }

    isPosting.value = true;

    try {
        // TODO: Implement comment posting logic
        commentInput.value = '';
    } catch (e) {
        // TODO: Handle error
    } finally {
        isPosting.value = false;
    }
};
</script>

<template>
    <Card>
        <CardContent>
            <h3 class="text-lg font-semibold">Comments</h3>
            <p class="text-sm text-accent-foreground/60">No comments yet</p>
        </CardContent>
    </Card>
    <Card>
        <CardContent>
            <div class="flex flex-col gap-2">
                <div class="grid grid-cols-1 gap-2 md:grid-cols-[auto_1fr]">
                    <Avatar class="hidden h-10 w-10 rounded-lg md:flex">
                        <AvatarImage v-if="userStore.link" :src="userStore.link" :alt="userStore.username" />
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
                    <Button size="sm" :disabled="isPosting" @click="handlePost">Post</Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
