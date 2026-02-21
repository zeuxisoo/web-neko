<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { CircleX } from 'lucide-vue-next';
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const hasTag = computed(() => {
    return route.query.tag && route.query.tag !== '';
});

const handleRemoveTag = () => {
    // set tag to empty when use push
    // delete route.query.tag should be use replace
    router.push({
        name: 'park.pulse',
        query: {
            ...route.query,
            tag: '',
        },
    });
};
</script>

<template>
    <div class="grid grid-cols-1" v-if="hasTag">
        <Card class="p-1.5">
            <CardContent>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="font-bold">Filter:</div>
                    <Button variant="outline" size="sm" class="round-md" @click="handleRemoveTag">
                        Tag
                        <CircleX />
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
