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
} from '@/components/base/alert-dialog';
import { useAlertDialog } from './useAlertDialog';

const AlertDialogTemplate = useAlertDialog();
</script>

<template>
    <AlertDialogTemplate v-slot="{ resolve, isResolving, args: [props] }">
        <AlertDialog :open="true">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>
                        {{ props.title }}
                    </AlertDialogTitle>
                    <AlertDialogDescription>
                        {{ props.description }}
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="resolve('cancel')">
                        {{ props.labelCancel ?? 'Cancel' }}
                    </AlertDialogCancel>
                    <AlertDialogAction @click="resolve('ok')">
                        {{ isResolving ? 'Confirming...' : (props.labelOk ?? 'Ok') }}
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AlertDialogTemplate>
</template>
