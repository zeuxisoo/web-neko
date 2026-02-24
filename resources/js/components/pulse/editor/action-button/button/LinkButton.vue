<script setup lang="ts">
import { Button } from '@/components/base/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/base/dialog';
import { Input } from '@/components/base/input';
import { ExternalLink, LinkIcon, LoaderIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const isLoading = ref(false);
const isOpen = ref(false);
const linkUrl = ref('https://example.com');

const emit = defineEmits<{
    (e: 'linked', links: string[]): void;
}>();

const handleSave = () => {
    // TODO: submit to backend
    emit('linked', [linkUrl.value]);

    isOpen.value = !isOpen.value;
};
</script>

<template>
    <div>
        <Dialog v-model:open="isOpen">
            <DialogTrigger as-child>
                <Button class="rounded-md" :disabled="isLoading">
                    <component :is="isLoading ? LoaderIcon : LinkIcon" :size="24" :class="{ 'animate-spin': isLoading }" />
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-full bg-primary/10">
                            <LinkIcon :size="20" class="text-primary" />
                        </div>
                        <div>
                            <DialogTitle class="text-xl">Insert Link</DialogTitle>
                            <DialogDescription class="mt-0.5 hidden text-sm md:block">Add a hyperlink to your content</DialogDescription>
                        </div>
                    </div>
                </DialogHeader>

                <div class="py-2">
                    <div class="space-y-2">
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <ExternalLink :size="16" class="text-muted-foreground" />
                            </div>
                            <Input id="link-url" v-model="linkUrl" type="url" placeholder="https://example.com" class="pl-10" />
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" @click="handleSave" :disabled="!linkUrl">Add</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
