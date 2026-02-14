<script setup lang="ts">
import api from '@/api';
import { useAlertDialog } from '@/components/alert-dialog/useAlertDialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { useMemosStore, useUserStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import { Ellipsis, Loader } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = withDefaults(
    defineProps<{
        memo: PulseMemoIndexResponse['data'][number];
        enableActionMore?: boolean;
    }>(),
    {
        enableActionMore: true,
    },
);

const emit = defineEmits<{
    edit: [];
}>();

const isLoading = ref(false);

const userStore = useUserStore();
const memosStore = useMemosStore();
const alertDialog = useAlertDialog();

const isAuthor = computed(() => {
    return userStore.id === props.memo.user.id;
});

const handleEdit = () => {
    emit('edit');
};

const handleDelete = async () => {
    const dialogResult = await alertDialog.start({
        title: 'Are you sure delete this memo?',
        description:
            'Note: This action cannot be undone. This will permanently remove this memo, attachment and related data record from our servers.',
    });

    if (dialogResult === 'ok') {
        try {
            isLoading.value = true;

            const { data, error } = await api.pulse.memo.destroy(props.memo.id).json<PulseMemoDestroyResponse>();

            if (error.value) {
                throw error.value;
            }

            if (data && data.value) {
                memosStore.remove(props.memo.id);

                toast.info('Memo destroyed');
            } else {
                throw error.value;
            }
        } catch (e: unknown) {
            WhoopsHandler.handleError(e, 'Unknown error on handle delete memo action');
        } finally {
            isLoading.value = false;
        }
    }
};

const handleNoMoreAction = () => {
    toast.info("No more action in comment page");
}
</script>

<template>
    <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
        <DropdownMenu v-if="props.enableActionMore">
            <DropdownMenuTrigger as-child>
                <Ellipsis :size="16" />
            </DropdownMenuTrigger>
            <DropdownMenuContent>
                <DropdownMenuItem :disabled="!isAuthor || isLoading" @click="handleEdit">Edit</DropdownMenuItem>
                <DropdownMenuItem :disabled="!isAuthor || isLoading" @click="handleDelete">
                    <Loader v-if="isLoading" />
                    <template v-else>Delete</template>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
        <Ellipsis v-else :size="16" @click="handleNoMoreAction"" />
    </div>
</template>
