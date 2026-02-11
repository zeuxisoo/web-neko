<script setup lang="ts">
import { useAlertDialog } from '@/components/alert-dialog/useAlertDialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { useUserStore } from '@/stores';
import { Ellipsis } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
    onEdit: () => void;
}>();

const userStore = useUserStore();
const alertDialog = useAlertDialog();

const isAuthor = computed(() => {
    return userStore.id === props.memo.user.id;
});

const handleEdit = () => {
    props.onEdit();
};

const handleDelete = async () => {
    const dialogResult = await alertDialog.start({
        title: 'Are you sure delete this memo?',
        description:
            'Note: This action cannot be undone. This will permanently remove this memo, attachment and related data record from our servers.',
    });

    if (dialogResult === 'ok') {
        console.log('deleted');
    } else {
        console.log('cancelled');
    }
};
</script>

<template>
    <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Ellipsis :size="16" />
            </DropdownMenuTrigger>
            <DropdownMenuContent>
                <DropdownMenuItem :disabled="!isAuthor" @click="handleEdit">Edit</DropdownMenuItem>
                <DropdownMenuItem :disabled="!isAuthor" @click="handleDelete">Delete</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
