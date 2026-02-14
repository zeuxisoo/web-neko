<script setup lang="ts">
import api from '@/api';
import { Card, CardContent } from '@/components/base/card';
import { Attachment } from '@/components/pulse/editor/types';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';

const isLoading = ref(true);
const attachments = ref<Attachment[]>([]);

const fetchAttachments = async () => {
    isLoading.value = true;

    try {
        const { data, error } = await api.pulse.attachment.index().json<PulseAttachmentIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            attachments.value = data.value.data;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch attachment list action in attachment page');
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchAttachments();
});
</script>

<template>
    <div class="attachment grid gap-3">
        <Card>
            <CardContent>
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight">Attachments</h1>
                        <span
                            v-if="attachments.length > 0"
                            class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary"
                        >
                            {{ attachments.length }}
                        </span>
                    </div>
                </div>

                <div v-if="isLoading" class="flex h-64 items-center justify-center">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
                </div>

                <div v-else-if="attachments.length === 0" class="flex h-64 flex-col items-center justify-center rounded-lg border-2 border-dashed">
                    <p class="text-sm text-muted-foreground">No attachments yet</p>
                </div>

                <div v-else class="grid grid-cols-2 gap-4 md:grid-cols-6 xl:grid-cols-8">
                    <div v-for="attachment in attachments" :key="attachment.id" class="aspect-square overflow-hidden rounded-lg border border-border">
                        <img :src="attachment.links.cover" :alt="attachment.original_name" class="h-full w-full object-cover" loading="lazy" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
