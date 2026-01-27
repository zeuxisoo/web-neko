<script setup lang="ts">
import api from '@/api';
import { Editor, MessageList, Pagination } from '@/components/pulse';
import { Attachment, SubmitData, TagOrderedList } from '@/components/pulse/editor/types';
import { WhoopsHandler } from '@/utils';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

type Tag = {
    id: number;
    name: string;
    order_column: number;
};

const isLoading = ref(false);
const editor = ref('');
const tags = ref<TagOrderedList>({});
const attachments = ref<Attachment[]>([]);

(async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.tag.all().json<PulseTagResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const resultTags = data.value.data;

            tags.value = convertToTagList(resultTags);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch tag action in park pulse');
    } finally {
        isLoading.value = false;
    }
})();

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
            console.log('1');
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

// convert Tag[] `[{ id, name, order_column }]` to `{ name: order_column }`
function convertToTagList(tags: Tag[]): TagOrderedList {
    return tags.reduce<Record<string, number>>((acc, tag) => {
        acc[tag.name] = tag.id;
        return acc;
    }, {});
}
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
