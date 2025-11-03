<script setup lang="ts">
import { Card, CardHeader } from '@/components/base/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Bookmark, Ellipsis, MessageSquareMore } from 'lucide-vue-next';
import { Badge } from '@/components/base/badge';

const remoteImages = [
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
    { src: 'https://placecats.com/512/512', title: 'cat1' },
]
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
</template>
