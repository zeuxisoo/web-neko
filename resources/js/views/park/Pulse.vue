<script setup lang="ts">
import api from '@/api';
import { Editor, MessageList, Pagination } from '@/components/pulse';
import { TagList } from '@/components/pulse/Editor/types';
import { WhoopsHandler } from '@/utils';
import { ref } from 'vue';

type Tag = {
    id: number;
    name: string;
    order_column: number;
};

const isLoading = ref(false);
const tagList = ref<TagList>({});

(async () => {
    try {
        isLoading.value = true;

        const { data, error } = await api.pulse.tag.all().json<PulseTagResponse>();

        if (data && data.value) {
            const resultTags = data.value.data;

            tagList.value = convertToTagList(resultTags);
        } else {
            throw error;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch tag action in park pulse');
    } finally {
        isLoading.value = false;
    }
})();

// convert Tag[] `[{ id, name, order_column }]` to `{ name: order_column }`
function convertToTagList(tags: Tag[]): Record<string, number> {
    return tags.reduce<Record<string, number>>((acc, tag) => {
        acc[tag.name] = tag.id;
        return acc;
    }, {});
}
</script>

<template>
    <div class="pulse grid gap-3">
        <Editor :tag-list="tagList" />
        <MessageList />
        <Pagination />
    </div>
</template>
