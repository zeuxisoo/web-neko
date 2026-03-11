<script setup lang="ts">
import api from '@/api';
import { useAlertDialog } from '@/components/alert-dialog';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { Input } from '@/components/base/input';
import { Textarea } from '@/components/base/textarea';
import { EmptyState } from '@/components/page';
import { TagsInput } from '@/components/tags-input';
import { useDriftsStore, useTagsStore } from '@/stores';
import { humanDateTime, WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { ChevronLeft, ChevronRight, EllipsisVertical, Loader } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { toast } from 'vue-sonner';

const isSubmitting = ref(false);
const subject = ref('');
const content = ref('');
const selectedTags = ref<string[]>([]);
const showRawDateTime = ref(false);
const isDeleting = ref(false);

const router = useRouter();
const route = useRoute();
const alertDialog = useAlertDialog();
const driftStore = useDriftsStore();
const tagsStore = useTagsStore();

const availableTags = computed(() => {
    return Object.keys(tagsStore.driftTags).map((name) => ({
        value: String(tagsStore.driftTags[name]),
        label: name,
    }));
});

const handleSubmit = async () => {
    try {
        isSubmitting.value = true;

        const formData = validator.form('drift.store').validate({
            subject: subject.value.trim(),
            content: content.value.trim(),
            tags: selectedTags.value,
        });

        const { data, error } = await api.drift.main.store(formData as DriftStorePayload).json<DriftStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const drift = result.data;

            driftStore.prepend(drift);

            // cleanup form
            subject.value = '';
            content.value = '';
            selectedTags.value = [];

            toast.info('Drift created');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle drift submit action');
    } finally {
        isSubmitting.value = false;
    }
};

const handlePrev = () => {
    if (!driftStore.drifts?.links.prev || !driftStore.drifts?.meta) return;

    const prevPage = (driftStore.drifts.meta.current_page ?? 1) - 1;

    router.push({
        name: 'drift',
        query: {
            ...router.currentRoute.value.query,
            page: prevPage,
        },
    });
};

const handleNext = () => {
    if (!driftStore.drifts?.links.next || !driftStore.drifts?.meta) return;

    const nextPage = (driftStore.drifts.meta.current_page ?? 1) + 1;

    router.push({
        name: 'drift',
        query: {
            ...router.currentRoute.value.query,
            page: nextPage,
        },
    });
};

const handleDelete = async (id: number) => {
    const dialogResult = await alertDialog.start({
        title: 'Are you sure delete this drift?',
        description: 'Note: This action cannot be undone. This will permanently remove this drift and related data record from our servers.',
    });

    if (dialogResult === 'ok') {
        try {
            isDeleting.value = true;

            const { data, error } = await api.drift.main.destroy(id).json<DriftDestroyResponse>();

            if (error.value) {
                throw error.value;
            }

            if (data && data.value) {
                const result = data.value;

                driftStore.remove(id);

                toast.info(result.message);
            } else {
                throw error.value;
            }
        } catch (e: unknown) {
            WhoopsHandler.handleError(e, 'Unknown error when remove drift action in drift page');
        } finally {
            isDeleting.value = false;
        }
    }
};

watch(
    () => route.query.page,
    () => {
        const page = Number(route.query.page) || 1;

        driftStore.fetchList(page);
    },
    { immediate: true },
);

onMounted(() => {
    tagsStore.fetchDrift();
});
</script>

<template>
    <div class="drift grid gap-3">
        <Card>
            <CardContent class="grid gap-4">
                <div class="grid gap-2">
                    <Input v-model="subject" placeholder="Subject" />
                </div>
                <div class="grid gap-2">
                    <Textarea v-model="content" placeholder="What's on your mind?" />
                </div>
                <div class="grid gap-2">
                    <TagsInput v-model="selectedTags" :remote-tags="availableTags" />
                </div>
                <div class="flex justify-end">
                    <Button @click="handleSubmit" :disabled="isSubmitting">
                        <Loader v-if="isSubmitting" class="animate-spin" />
                        <template v-else>Post</template>
                    </Button>
                </div>
            </CardContent>
        </Card>

        <template v-if="driftStore.drifts && driftStore.drifts.data.length > 0">
            <Card class="gap-3" v-for="drift in driftStore.drifts.data" :key="drift.id">
                <CardHeader class="gap-0">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <Avatar class="h-10 w-10 rounded-lg">
                                <AvatarImage :src="drift.user.link" :alt="drift.user.username" />
                                <AvatarFallback class="rounded-lg text-sm">{{ drift.user.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
                            </Avatar>
                            <div class="grid flex-1 text-left text-sm leading-6">
                                <span class="truncate font-semibold">{{ drift.user.username }}</span>
                                <span class="flex items-center text-xs text-accent-foreground/80" @click="showRawDateTime = !showRawDateTime">
                                    {{ humanDateTime(drift.created_at, showRawDateTime) }}
                                </span>
                            </div>
                        </div>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child :disabled="isDeleting">
                                <EllipsisVertical :size="16" />
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuItem>Edit</DropdownMenuItem>
                                <DropdownMenuItem @click="handleDelete(drift.id)" :disabled="isDeleting">
                                    <Loader v-if="isDeleting" />
                                    <template v-else>Delete</template>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </CardHeader>
                <CardContent class="grid gap-2">
                    <p class="rounded-md border bg-accent/35 p-2 font-semibold">{{ drift.subject }}</p>
                    <p class="rounded-md border p-2 hover:bg-accent/80">{{ drift.content }}</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <Badge v-for="tag in drift.tags" :key="tag.id" variant="secondary"> #{{ tag.name }} </Badge>
                    </div>
                </CardContent>
            </Card>
            <Card class="py-3">
                <CardContent>
                    <div class="flex w-full justify-between">
                        <Button class="cursor-pointer" :disabled="!driftStore.drifts.links.prev" @click="handlePrev"> <ChevronLeft />Prev </Button>
                        <Button class="cursor-pointer" :disabled="!driftStore.drifts.links.next" @click="handleNext"> Next<ChevronRight /> </Button>
                    </div>
                </CardContent>
            </Card>
        </template>

        <EmptyState :is-loading="driftStore.isLoading" message="No drifts yet" v-if="!driftStore.drifts || driftStore.drifts.data.length <= 0" />
    </div>
</template>
