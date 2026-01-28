<script setup lang="ts">
import api from '@/api';
import { Editor, MessageList, Pagination } from '@/components/pulse';
import { Attachment, SubmitData, TagOrderedList } from '@/components/pulse/editor/types';
import { fillAttachments, WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const isLoading = ref(false);
const editor = ref('');
const tags = ref<TagOrderedList>({});
const attachments = ref<Attachment[]>([]);

onMounted(() => Promise.all([fetchTags(), fetchUnsavedAttachments()]));

const fetchTags = async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.tag.all().json<PulseTagResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const resultTags = data.value.data;

            // convert Tag[] `[{ id, name, order_column }]` to TagOrderedList `{ name: order_column }`
            tags.value = resultTags.reduce<Record<string, number>>((acc, tag) => {
                acc[tag.name] = tag.id;
                return acc;
            }, {} as TagOrderedList);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch tag list action in park pulse');
    } finally {
        isLoading.value = false;
    }
};

const fetchUnsavedAttachments = async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.attachment.unsaved().json<PulseAttachmentUploadResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const attachmentList = result.data;

            for (const attachment of attachmentList) {
                fillAttachments(attachments.value, attachment);
            }
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch unsaved attachment list action in park pulse');
    } finally {
        isLoading.value = false;
    }
};

const handleUploaded = (files: Attachment[]) => {
    attachments.value = attachments.value.concat(files);
};

const handleAttachmentUp = (index: number) => {
    if (index <= 0) return;

    const item = attachments.value[index];

    attachments.value[index] = attachments.value[index - 1];
    attachments.value[index - 1] = item;
};

const handleAttachmentDown = (index: number) => {
    if (index >= attachments.value.length - 1) return;

    const item = attachments.value[index];

    attachments.value[index] = attachments.value[index + 1];
    attachments.value[index + 1] = item;
};

const handleAttachmentRemove = async (index: number) => {
    try {
        const attachment = attachments.value[index];

        const { data, error } = await api.pulse.attachment.destroy(attachment.id).json<PulseAttachmentDestroyResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const message = result.message;

            toast.info(message);

            attachments.value.splice(index, 1);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when remove attachment action in park pulse');
    }
};

const handleSubmit = (data: SubmitData) => {
    console.log(data);
    console.log(editor.value);
};
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor
            v-model="editor"
            :tags="tags"
            :attachments="attachments"
            @uploaded="handleUploaded"
            @attachmentUp="handleAttachmentUp"
            @attachmentDown="handleAttachmentDown"
            @attachmentRemove="handleAttachmentRemove"
            @submit="handleSubmit"
        />
        <MessageList />
        <Pagination />
    </div>
</template>
