<script setup lang="ts">
import { useAlertDialog } from '@/components/alert-dialog/useAlertDialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { Ellipsis } from 'lucide-vue-next';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const dialog = useAlertDialog();

const handleDelete = async () => {
    const dialogResult = await dialog.start({
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
                <DropdownMenuItem>Edit</DropdownMenuItem>
                <DropdownMenuItem @click="handleDelete">Delete</DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
