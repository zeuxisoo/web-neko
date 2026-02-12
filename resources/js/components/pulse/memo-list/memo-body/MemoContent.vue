<script setup lang="ts">
import { extractHashTags } from '@/utils';
import { computed } from 'vue';

const props = defineProps<{
    content: string;
}>();

const segments = computed(() => {
    const content = props.content;
    const tags = extractHashTags(props.content);

    if (tags.length <= 0) {
        return [
            {
                text: props.content,
                isTag: false,
            },
        ];
    }

    // 1. escape tags for regex (handles special chars like ?)
    // 2. sort by length (descending) so #abc😂 is matched before #abc
    // prettier-ignore
    const escapedTags = tags
        .map((t) => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'))
        .sort((a, b) => b.length - a.length);

    // 3. create a capture group regex: (#beat|#abc😂|#你好嗎)
    const pattern = new RegExp(`(${escapedTags.join('|')})`, 'g');

    // 4. split and map type of each part
    return content.split(pattern).map((part) => ({
        text: part,
        isTag: tags.includes(part),
    }));
});
</script>

<template>
    <span class="whitespace-pre-line">
        <template v-for="(segment, i) in segments" :key="i">
            <!-- /search?tag=${segment.text.slice(1)} -->
            <router-link
                v-if="segment.isTag"
                :to="{ name: 'park.pulse', query: { tag: segment.text.slice(1) } }"
                class="text-blue-500 hover:text-blue-700"
            >
                {{ segment.text }}
            </router-link>

            <template v-else>{{ segment.text }}</template>
        </template>
    </span>
</template>
