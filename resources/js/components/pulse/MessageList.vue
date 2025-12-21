<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Card, CardHeader } from '@/components/base/card';
import { Skeleton } from '@/components/base/skeleton';
import { Bookmark, Ellipsis, MessageSquareMore } from 'lucide-vue-next';

const remoteImages = [
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
];

const totalRemoteImages = Math.round(6 / remoteImages.length);
const placeHolderImages = new Array(totalRemoteImages).fill(0);
</script>

<template>
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
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                    enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </div>
                <div class="grid grid-cols-2 gap-2 md:grid-cols-6">
                    <div class="flex justify-center" v-for="(image, i) in remoteImages" :key="i">
                        <img
                            :src="image.src"
                            :title="image.title"
                            class="max-h-48 cursor-pointer rounded-lg border object-cover"
                            decoding="async"
                            loading="lazy"
                        />
                    </div>
                    <template v-for="i in placeHolderImages" :key="i">
                        <div class="flex justify-center">
                            <Skeleton class="aspect-square max-h-48 w-48 cursor-pointer rounded-lg border" />
                        </div>
                    </template>
                </div>
                <div class="flex flex-row gap-2">
                    <Badge variant="secondary" v-for="i in [1, 2, 3, 4]" :key="i"> #Tag1 </Badge>
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
