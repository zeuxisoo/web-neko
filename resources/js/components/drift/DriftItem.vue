<script setup lang="ts">
import api from '@/api';
import { useAlertDialog } from '@/components/alert-dialog';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { Badge } from '@/components/base/badge';
import { Card, CardContent, CardHeader } from '@/components/base/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { useDriftsStore } from '@/stores';
import { humanDateTime, WhoopsHandler } from '@/utils';
import { EllipsisVertical, Loader } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    drift: DriftIndexResponse['data'][number];
}>();

const isDeleting = ref(false);
const alertDialog = useAlertDialog();
const driftStore = useDriftsStore();

const showRawDateTime = ref(false);

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
</script>

<template>
    <Card class="gap-3">
        <CardHeader class="gap-0">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <Avatar class="h-10 w-10 rounded-lg">
                        <AvatarImage :src="props.drift.user.link" :alt="props.drift.user.username" />
                        <AvatarFallback class="rounded-lg text-sm">{{ props.drift.user.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
                    </Avatar>
                    <div class="grid flex-1 text-left text-sm leading-6">
                        <span class="truncate font-semibold">{{ props.drift.user.username }}</span>
                        <span class="flex items-center text-xs text-accent-foreground/80" @click="showRawDateTime = !showRawDateTime">
                            {{ humanDateTime(props.drift.created_at, showRawDateTime) }}
                        </span>
                    </div>
                </div>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child :disabled="isDeleting">
                        <EllipsisVertical :size="16" />
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuItem>Edit</DropdownMenuItem>
                        <DropdownMenuItem @click="handleDelete(props.drift.id)" :disabled="isDeleting">
                            <Loader v-if="isDeleting" />
                            <template v-else>Delete</template>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </CardHeader>
        <CardContent class="grid gap-2">
            <p class="rounded-md border bg-accent/35 p-2 font-semibold">{{ props.drift.subject }}</p>
            <p class="rounded-md border p-2 hover:bg-accent/80">{{ props.drift.content }}</p>
            <div class="mt-3 flex flex-wrap gap-3">
                <Badge v-for="tag in props.drift.tags" :key="tag.id" variant="secondary"> #{{ tag.name }} </Badge>
            </div>
        </CardContent>
    </Card>
</template>
