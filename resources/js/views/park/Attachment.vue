<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Attachment } from '@/components/pulse/editor/types';
import { WhoopsHandler } from '@/utils';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

const isLoading = ref(true);
const attachments = ref<PulseAttachmentIndexResponse>();

// group attachments by year
const attachmentsByYear = computed(() => {
    const groups: Record<string, Attachment[]> = {};

    for (const attachment of attachments.value?.data ?? []) {
        const year = new Date(attachment.created_at).getFullYear().toString();

        if (!groups[year]) {
            groups[year] = [];
        }

        groups[year].push(attachment);
    }

    // sort years in descending order (newest first)
    return Object.entries(groups).sort((a, b) => b[0].localeCompare(a[0]));
});

const fetchAttachments = async () => {
    isLoading.value = true;

    try {
        const page = Number(router.currentRoute.value.query.page) || 1;
        const cursor = router.currentRoute.value.query.cursor ? Number(router.currentRoute.value.query.cursor) : undefined;

        const { data, error } = await api.pulse.attachment.index({ page, cursor }).json<PulseAttachmentIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            attachments.value = data.value;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch attachment list action in attachment page');
    } finally {
        isLoading.value = false;
    }
};

const handlePrev = () => {
    if (!attachments.value?.links.prev || !attachments.value?.meta) return;

    const prevPage = attachments.value.meta.current_page - 1;

    router.push({
        name: 'park.attachment',
        query: {
            ...router.currentRoute.value.query,
            page: prevPage,
            cursor: attachments.value.meta.last_year,
        },
    });
};

const handleNext = () => {
    if (!attachments.value?.links.next || !attachments.value?.meta) return;

    const nextPage = attachments.value.meta.current_page + 1;

    router.push({
        name: 'park.attachment',
        query: {
            ...router.currentRoute.value.query,
            page: nextPage,
            cursor: attachments.value.meta.first_year,
        },
    });
};

watch(
    [() => route.query.page, () => route.query.cursor],
    () => {
        fetchAttachments();
    },
    { immediate: true },
);
</script>

<template>
    <div class="attachment grid gap-3">
        <Card class="py-3">
            <CardContent>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight">Attachments</h1>
                        <span
                            v-if="attachments?.data && attachments.data.length > 0"
                            class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ attachments.data.length }}
                        </span>
                    </div>
                </div>

                <div class="mt-3" v-if="isLoading && (!attachments?.data || attachments.data.length === 0)">
                    <div v-if="isLoading" class="flex h-64 items-center justify-center">
                        <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
                    </div>

                    <div
                        v-else-if="!attachments?.data || attachments.data.length === 0"
                        class="flex h-64 flex-col items-center justify-center rounded-lg border-2 border-dashed"
                    >
                        <p class="text-sm text-muted-foreground">No attachments yet</p>
                    </div>
                </div>
            </CardContent>
        </Card>

        <template v-if="attachments?.data && attachments.data.length > 0">
            <Card v-for="[year, yearAttachments] in attachmentsByYear" :key="year">
                <CardContent>
                    <h2 class="mb-4 text-xl font-semibold tracking-tight">{{ year }}</h2>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-6 xl:grid-cols-8">
                        <div
                            v-for="attachment in yearAttachments"
                            :key="attachment.id"
                            class="aspect-square overflow-hidden rounded-lg border border-border"
                        >
                            <img :src="attachment.links.cover" :alt="attachment.original_name" class="h-full w-full object-cover" loading="lazy" />
                        </div>
                    </div>
                </CardContent>
            </Card>
        </template>
        <Card class="py-3" v-if="attachments?.links">
            <CardContent>
                <div class="flex w-full justify-between">
                    <Button class="cursor-pointer" :disabled="!attachments.links.prev" @click="handlePrev"> <ChevronLeft />Prev </Button>
                    <Button class="cursor-pointer" :disabled="!attachments.links.next" @click="handleNext"> Next<ChevronRight /> </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
