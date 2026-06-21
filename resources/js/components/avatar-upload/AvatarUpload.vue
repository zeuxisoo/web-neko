<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { useFileDialog } from '@vueuse/core';
import { CircleUserRoundIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '../base/button';

const props = withDefaults(
    defineProps<{
        url: string;
        name: string;
        enableRemove?: boolean;
    }>(),
    {
        enableRemove: false,
    },
);

const emit = defineEmits<{
    (e: 'change', value: File): void;
    (e: 'preview'): void;
}>();

const previewUrl = ref<string>(props.url);
const fileName = ref<string>(props.name);

const { open, onChange } = useFileDialog({
    multiple: false,
    accept: 'image/*',
    reset: true,
});

onChange((files) => {
    if (files) {
        previewUrl.value = URL.createObjectURL(files[0]);
        fileName.value = files[0].name;

        emit('change', files[0]);
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
            <Avatar class="size-9 cursor-pointer rounded-md border border-input" @click="emit('preview')">
                <AvatarImage v-if="previewUrl" :src="previewUrl" class="size-full object-cover" />
                <AvatarFallback class="size-full">
                    <CircleUserRoundIcon class="opacity-60" :size="16" />
                </AvatarFallback>
            </Avatar>
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
            <button v-if="props.enableRemove" @click="removeFile" class="cursor-pointer font-medium text-destructive hover:underline">Remove</button>
        </div>
        <div class="inline-flex gap-2 text-xs" v-else>
            <p class="truncate text-muted-foreground">No image attached</p>
        </div>
    </div>
</template>
