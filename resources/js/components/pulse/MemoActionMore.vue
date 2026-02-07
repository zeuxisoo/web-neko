<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/base/alert-dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/base/dropdown-menu';
import { Ellipsis } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    memo: PulseMemoIndexResponse['data'][number];
}>();

const isDropdownMenuOpen = ref(false);

const handleDeleteClicked = () => {
    isDropdownMenuOpen.value = false;
};
</script>

<template>
    <div class="inline-flex items-center justify-center gap-2 py-2 text-sm font-medium">
        <AlertDialog>
            <DropdownMenu v-model:open="isDropdownMenuOpen">
                <DropdownMenuTrigger as-child>
                    <Ellipsis :size="16" />
                </DropdownMenuTrigger>
                <DropdownMenuContent>
                    <DropdownMenuItem>Edit</DropdownMenuItem>
                    <AlertDialogTrigger as-child>
                        <DropdownMenuItem @select.prevent @click="handleDeleteClicked">
                            <div>Delete</div>
                        </DropdownMenuItem>
                    </AlertDialogTrigger>
                </DropdownMenuContent>
            </DropdownMenu>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle> Are you sure delete this memo? </AlertDialogTitle>
                    <AlertDialogDescription>
                        Note: This action cannot be undone. This will permanently remove this memo, attachment and related data record from our
                        servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <AlertDialogAction>Continue</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
