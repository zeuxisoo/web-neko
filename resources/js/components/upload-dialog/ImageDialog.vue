<script setup lang="ts">
import { CircleX, ImageUp, Upload } from 'lucide-vue-next';
import { Button } from '@/components/base/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/base/dialog';
import VueFilePond from 'vue-filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

const FilePond = VueFilePond(
    FilePondPluginFileValidateSize,
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
                    <FilePond
                        allow-multiple="true"
                        server="/pulse/upload-images"
                        instant-upload="false"
                        max-file-size="8MB"
                        label-max-file-size="Only allow {filesize}"
                        accepted-file-types="image/jpeg, image/jpg, image/png, image/webp"
                        label-file-type-not-allowed="Wrong file type"
                        file-validate-type-label-expected-types="Expects images"
                        image-preview-max-height="80" />
                </div>
            </div>
            <DialogFooter class="grid grid-cols-2">
                <Button type="button">
                    <Upload /> Upload
                </Button>
                <DialogClose as-child>
                    <Button type="button" variant="secondary">
                        <CircleX /> Close
                    </Button>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
