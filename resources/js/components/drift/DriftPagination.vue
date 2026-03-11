<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const props = defineProps<{
    links: DriftIndexResponse['links'] | undefined;
    meta: DriftIndexResponse['meta'] | undefined;
}>();

const router = useRouter();

const handlePrev = () => {
    if (!props.links || !props.meta) return;

    const prevPage = (props.meta.current_page ?? 1) - 1;

    router.push({
        name: 'drift',
        query: {
            ...router.currentRoute.value.query,
            page: prevPage,
        },
    });
};

const handleNext = () => {
    if (!props.links || !props.meta) return;

    const nextPage = (props.meta.current_page ?? 1) + 1;

    router.push({
        name: 'drift',
        query: {
            ...router.currentRoute.value.query,
            page: nextPage,
        },
    });
};
</script>

<template>
    <Card class="py-3" v-if="props.links">
        <CardContent>
            <div class="flex w-full justify-between">
                <Button class="cursor-pointer" :disabled="!props.links.prev" @click="handlePrev"> <ChevronLeft />Prev </Button>
                <Button class="cursor-pointer" :disabled="!props.links.next" @click="handleNext"> Next<ChevronRight /> </Button>
            </div>
        </CardContent>
    </Card>
</template>
