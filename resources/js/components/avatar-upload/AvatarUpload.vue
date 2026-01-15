<script setup lang="ts">
import { useFileDialog } from '@vueuse/core';
import { CircleUserRoundIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '../base/button';

const previewUrl = ref<string>();
const fileName = ref<string>();

const { open, onChange } = useFileDialog({
    multiple: false,
    accept: 'image/*',
    reset: true,
});

onChange((files) => {
    if (files) {
        previewUrl.value = URL.createObjectURL(files[0]);
        fileName.value = files[0].name;
    }
});

const removeFile = () => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);

        previewUrl.value = '';
        fileName.value = '';
    }
};
</script>

<template>
    <div class="flex flex-col items-center gap-2">
        <div class="inline-flex items-center gap-2 align-top">
            <div class="relative flex size-9 shrink-0 items-center justify-center overflow-hidden rounded-md border border-input">
                <img v-if="previewUrl" :src="previewUrl" class="size-full object-cover" width="32" height="32" />
                <div v-else>
                    <CircleUserRoundIcon class="opacity-60" :size="16" />
                </div>
            </div>
            <div class="relative inline-block">
                <Button @click="open">
                    <span v-if="fileName">Change image</span>
                    <span v-else>Upload image</span>
                </Button>
                <input class="sr-only" />
            </div>
        </div>
        <div class="inline-flex gap-2 text-xs" v-if="fileName">
            <p class="truncate text-muted-foreground">
                {{ fileName }}
            </p>
            <button @click="removeFile" class="cursor-pointer font-medium text-destructive hover:underline">Remove</button>
        </div>
        <div class="inline-flex gap-2 text-xs" v-else>
            <p class="truncate text-muted-foreground">No image attached</p>
        </div>
    </div>
</template>
