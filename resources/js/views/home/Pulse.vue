<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { Textarea } from '@/components/base/textarea';
import TagsInput from '@/components/tags-input/TagsInput.vue';
import ImageDialog from '@/components/upload-dialog/ImageDialog.vue';
import { Bookmark, ChevronLeft, ChevronRight, Ellipsis, MessageSquareMore, SendHorizontal } from 'lucide-vue-next';
import { ref } from 'vue';

const remoteTags = [
    { value: 'apple', label: 'Apple' },
    { value: 'banana', label: 'Banana' },
    { value: 'cherry', label: 'Cherry' },
    { value: 'durian', label: 'Durian' },
];

const remoteImages = [
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
]

const currentTags = ref<string[]>([]);

const submit = () => {
    console.log(currentTags.value);
};
</script>

<template>
    <div class="pulse grid gap-3">
        <Card>
            <CardContent>
                <div class="item-center grid w-full gap-4">
                    <div class="flex flex-col">
                        <Textarea name="content" class="min-h-[100px]" placeholder="Place whatever you want" />
                    </div>
                    <div class="flex w-full items-center">
                        <TagsInput v-model="currentTags" :remote-tags="remoteTags"  />
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <ImageDialog />
                        </div>
                        <Button @click="submit">
                            <SendHorizontal /> Submit
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <div class="flex items-center gap-2 text-left text-sm">
                    <Avatar class="h-12 w-12 rounded-lg">
                        <AvatarImage src="/avatars/username.png" />
                        <AvatarFallback class="rounded-lg"> AV </AvatarFallback>
                    </Avatar>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">Username</span>
                        <span class="truncate text-xs">20 hours ago</span>
                    </div>
                </div>
                <div class="grid gap-2">
                    <div class="font-light">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </div>
                    <div class="columns-2 md:columns-6">
                        <div class="flex justify-center" v-for="(image, i) in remoteImages" :key="i">
                            <img :src="image.src" :title="image.title" class="object-cover max-h-48 cursor-pointer border border-border/60 rounded-lg" decoding="async" loading="lazy" />
                        </div>
                    </div>
                    <div class="flex flex-row gap-2">
                        <Badge variant="secondary" v-for="i in [1, 2, 3, 4]" :key="i">
                            #Tag1
                        </Badge>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="comment">
                            <div class="inline-flex items-center justify-center gap-2 text-sm font-medium py-2">
                                <MessageSquareMore :size="16" /> 0
                            </div>
                        </div>
                        <div class="bookmark flex justify-center">
                            <div class="inline-flex items-center justify-center gap-2 text-sm font-medium py-2">
                                <Bookmark :size="16" />
                            </div>
                        </div>
                        <div class="action flex justify-end">
                            <div class="inline-flex items-center justify-center gap-2 text-sm font-medium py-2">
                                <Ellipsis :size="16" />
                            </div>
                        </div>
                    </div>
                </div>
            </CardHeader>
        </Card>
        <Card class="py-3">
            <CardContent>
                <div class="flex w-full justify-between">
                    <Button class="gap-1">
                        <ChevronLeft />Prev
                    </Button>
                    <Button class="gap-1">
                        Next<ChevronRight />
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
