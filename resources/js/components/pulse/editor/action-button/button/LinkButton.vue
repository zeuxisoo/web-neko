<script setup lang="ts">
import api from '@/api';
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
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/base/input-group';
import { Label } from '@/components/base/label';
import { Separator } from '@/components/base/separator';
import { WhoopsHandler } from '@/utils';
import { formatISO } from 'date-fns';
import { ExternalLink, LinkIcon, LoaderIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { Link } from '../../types';

const isLoading = ref(false);
const isOpen = ref(false);
const linkUrl = ref('https://example.com');
const fetchedLink = ref<Link>();

const emit = defineEmits<{
    (e: 'linked', link: Link): void;
}>();

const handleFetch = async () => {
    isLoading.value = true;

    try {
        const { data, error } = await api.pulse.link.fetch({ url: linkUrl.value }).json<PulseLinkFetchResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data.value && data.value.ok) {
            const result = data.value;

            fetchedLink.value = {
                id: 0, // fetch only no db records
                url: result.data.url,
                title: result.data.title || '',
                description: result.data.description || '',
                image: result.data.image || '',
                created_at: formatISO(new Date()),
            };
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle fetch link action');
    } finally {
        isLoading.value = false;
    }
};

const handleSave = async () => {
    if (fetchedLink.value) {
        isLoading.value = true;

        try {
            const { data, error } = await api.pulse.link
                .store({
                    url: fetchedLink.value.url,
                    title: fetchedLink.value.title,
                    description: fetchedLink.value.description,
                    image: fetchedLink.value.image,
                })
                .json<PulseLinkStoreResponse>();

            if (error.value) {
                throw error.value;
            }

            if (data.value && data.value.ok) {
                const result = data.value;
                const link = result.data;

                emit('linked', {
                    ...fetchedLink.value,
                    id: link.id, // update to stored db records
                    created_at: link.created_at,
                });

                toast.success('Link added successfully');
            } else {
                throw error.value;
            }
        } catch (e: unknown) {
            WhoopsHandler.handleError(e, 'Unknown error on handle save link action');
        } finally {
            isLoading.value = false;
        }
    }

    isOpen.value = !isOpen.value;

    fetchedLink.value = undefined;
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
                    <InputGroup>
                        <InputGroupInput id="link-url" v-model="linkUrl" placeholder="https://example.com" type="url" />
                        <InputGroupAddon>
                            <ExternalLink :size="16" />
                        </InputGroupAddon>
                    </InputGroup>
                    <Separator v-if="fetchedLink" class="my-4" />
                    <div v-if="fetchedLink">
                        <div class="flex gap-4">
                            <div class="flex-1 space-y-3">
                                <div>
                                    <Label for="link-title" class="text-xs text-muted-foreground">Title</Label>
                                    <Input id="link-title" v-model="fetchedLink.title" class="mt-1" />
                                </div>
                                <div>
                                    <Label for="link-description" class="text-xs text-muted-foreground">Description</Label>
                                    <Input id="link-description" v-model="fetchedLink.description" class="mt-1" />
                                </div>
                                <div class="flex justify-center">
                                    <img
                                        v-if="fetchedLink.image"
                                        :src="fetchedLink.image"
                                        class="mt-1 h-18 w-18 rounded-md object-cover"
                                        alt="Link image preview"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline" @click="fetchedLink = undefined">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" variant="secondary" @click="handleFetch" :disabled="!linkUrl || isLoading">
                        <LoaderIcon v-if="isLoading" :class="{ 'animate-spin': isLoading }" />
                        <template v-else>Fetch</template>
                    </Button>
                    <Button v-if="fetchedLink" type="submit" :disabled="isLoading" @click="handleSave">
                        <LoaderIcon v-if="isLoading" :class="{ 'animate-spin': isLoading }" />
                        <template v-else>Save</template>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
