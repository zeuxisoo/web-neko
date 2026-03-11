<script setup lang="ts">
import { EmptyState } from '@/components/page';
import { useDriftsStore } from '@/stores';
import { watch } from 'vue';
import { useRoute } from 'vue-router';
import DriftItem from './DriftItem.vue';
import DriftPagination from './DriftPagination.vue';

const driftStore = useDriftsStore();
const route = useRoute();

watch(
    () => route.query.page,
    () => {
        const page = Number(route.query.page) || 1;

        driftStore.fetchList(page);
    },
    { immediate: true },
);
</script>

<template>
    <template v-if="driftStore.drifts && driftStore.drifts.data.length > 0">
        <DriftItem :drift="drift" v-for="drift in driftStore.drifts.data" :key="drift.id" />
        <DriftPagination :links="driftStore.drifts.links" :meta="driftStore.drifts.meta" />
    </template>

    <EmptyState :is-loading="driftStore.isLoading" message="No drifts yet" v-if="!driftStore.drifts || driftStore.drifts.data.length <= 0" />
</template>
