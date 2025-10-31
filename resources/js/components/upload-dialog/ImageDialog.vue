<script setup lang="ts">
import { ImageUp } from 'lucide-vue-next';
import { Button } from '@/components/base/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/base/dialog';
import VueFilePond from 'vue-filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

const FilePond = VueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button>
                <ImageUp />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Upload Images</DialogTitle>
                <DialogDescription>What image do you want to upload?</DialogDescription>
            </DialogHeader>
            <div class="flex items-center space-x-2">
                <div class="grid flex-1 gap-2">
                    <FilePond allow-multiple="true" image-preview-max-height="80" />
                </div>
            </div>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button type="button">OK</Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
