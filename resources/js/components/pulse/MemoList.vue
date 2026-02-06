<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/base/alert';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { CircleX, Info } from 'lucide-vue-next';
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import MemoItem from './MemoItem.vue';
import MemoPagination from './MemoPagination.vue';

const props = defineProps<{
    memos: PulseMemoIndexResponse | undefined;
}>();

const route = useRoute();

const hasTag = computed(() => {
    return route.query.tag && route.query.tag !== '';
});
</script>

<template>
    <div class="grid grid-cols-1">
        <Card class="p-1.5" v-if="hasTag">
            <CardContent>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="font-bold">Filter:</div>
                    <Button variant="outline" size="sm" class="round-md">
                        Tag
                        <CircleX />
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
    <div class="grid grid-cols-1 gap-2" v-if="props.memos">
        <MemoItem :memo="memo" v-for="memo in props.memos.data" />
        <MemoPagination :links="props.memos.links" :meta="props.memos.meta" />
    </div>
    <div class="grid grid-cols-1 gap-2" v-else>
        <Card>
            <CardContent>
                <Alert>
                    <Info />
                    <AlertTitle>Oops!</AlertTitle>
                    <AlertDescription>Do you want to create first memo?</AlertDescription>
                </Alert>
            </CardContent>
        </Card>
    </div>
</template>
