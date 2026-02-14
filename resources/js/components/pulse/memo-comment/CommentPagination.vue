<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';

const props = defineProps<{
    links: PulseCommentIndexResponse['links'] | undefined;
    meta: PulseCommentIndexResponse['meta'] | undefined;
}>();

const emit = defineEmits<{
    loadMore: [];
}>();
</script>

<template>
    <Card v-if="props.links" class="py-3">
        <CardContent>
            <div class="flex w-full justify-center">
                <Button class="cursor-pointer" :disabled="!props.links.next" @click="emit('loadMore')">
                    <span v-if="props.links.next">Load more</span>
                    <span v-else>No more comments</span>
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
