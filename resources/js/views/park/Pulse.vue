<script setup lang="ts">
import api from '@/api';
import { Editor, MemoList } from '@/components/pulse';
import { Attachment, SubmitData, TagOrderedList } from '@/components/pulse/editor/types';
import { fillAttachments, WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { toast } from 'vue-sonner';

const isLoading = ref(false);
const editor = ref('');
const tags = ref<TagOrderedList>({});
const attachments = ref<Attachment[]>([]);
const extractedTags = ref<string[]>([]);
const memos = ref<PulseMemoIndexResponse>();

const route = useRoute();

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

const fetchMemoList = async () => {
    try {
        isLoading.value = true;

        const page = route.query.page ? Number(route.query.page) : 1;
        const tag = route.query.tag ? String(route.query.tag) : '';
        const { data, error } = await api.pulse.memo.index({ page, tag }).json<PulseMemoIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;

            memos.value = result;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch memo list action in park pulse');
    } finally {
        isLoading.value = false;
    }
};

const handleUploaded = (files: Attachment[]) => {
    attachments.value = attachments.value.concat(files);
};

const handleExtractedTags = (tags: string[]) => {
    extractedTags.value = tags;
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

const handleSubmit = async (_: SubmitData) => {
    const attachmentList = Object.entries(attachments.value).map(([k, attachment]: [string, Attachment], index: number) => {
        return {
            id: attachment.id,
            filename: attachment.filename,
            sort_order: index,
        };
    });

    try {
        isLoading.value = true;

        const formData = validator.form('pulse.memo.store').validate({
            content: editor.value,
            tags: extractedTags.value,
            attachments: attachmentList,
        });

        const { data, error } = await api.pulse.memo.store(formData as PulseMemoStorePayload).json<PulseMemoStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const memo = result.data;

            if (memos.value) {
                memos.value.data = [memo, ...memos.value.data];
            }

            editor.value = '';
            tags.value = {};
            extractedTags.value = [];
            attachments.value = [];

            toast.info('Memo created');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle account avatar save action');
    } finally {
        isLoading.value = false;
    }
};

watch(
    [() => route.query.page, () => route.query.tag],
    ([_pageNewVal, _tagNewVal], [_pageOldVal, _tagOldVal]) => {
        fetchMemoList();
    },
    { immediate: true },
);
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor
            v-model="editor"
            :isLoading="isLoading"
            :tags="tags"
            :attachments="attachments"
            @uploaded="handleUploaded"
            @extractedTags="handleExtractedTags"
            @attachmentUp="handleAttachmentUp"
            @attachmentDown="handleAttachmentDown"
            @attachmentRemove="handleAttachmentRemove"
            @submit="handleSubmit"
        />
        <MemoList :memos="memos" />
    </div>
</template>
