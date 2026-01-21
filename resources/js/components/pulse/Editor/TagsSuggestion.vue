<script setup lang="ts">
import { cn } from '@/lib/utils';
import Fuse from 'fuse.js';
import { computed, ref, watch } from 'vue';
import { EditorMethods, TagList } from './types';
import useSuggestions from './useSuggestions';

const props = defineProps<{
    editorRef: HTMLTextAreaElement | null;
    editorMethods: EditorMethods;
    tagList: TagList;
}>();

//
const containerElement = ref<HTMLDivElement>();
const selectedElement = ref<HTMLDivElement>();

const editorRef = computed(() => props.editorRef);

//
const sortedTags = Object.entries(props.tagList)
    .sort((a, b) => a[0].localeCompare(b[0]))
    .sort((a, b) => b[1] - a[1])
    .map(([tag]) => tag);

const { isVisible, position, selectedIndex, suggestionList, fireAutocomplete } = useSuggestions({
    editorRef: editorRef,
    editorMethods: props.editorMethods,
    triggerChar: '#',
    itemList: sortedTags,
    filterList: (items: string[], searchWord: string): string[] => {
        if (searchWord === '') {
            return items;
        }

        return new Fuse(items).search(searchWord).map((result) => result.item);
    },
    onSelectedItem: (item, word, index) => {
        props.editorMethods.removeText(index, word.length);
        props.editorMethods.insertText('#' + item + ' ');
    },
});

watch(isVisible, () => console.log(`visible: ${isVisible}`));

//
watch(
    () => selectedIndex.value,
    () => {
        if (selectedElement.value && containerElement.value) {
            selectedElement.value.scrollIntoView({
                block: 'nearest',
                behavior: 'smooth',
            });
        }
    },
);

//
const tagsListPosition = computed(() => {
    const pos = position.value;

    if (!pos) {
        return { left: 0, right: 0 };
    }

    return {
        left: pos.left + 'px',
        top: pos.top + pos.height + 'px',
        'font-weight': 'bold',
    };
});
</script>

<template>
    <!--TODO fix position-->
    <div
        class="absolute z-20 flex max-w-48 flex-col overflow-auto rounded bg-white shadow dark:bg-black"
        :style="tagsListPosition"
        ref="containerElement"
        v-if="isVisible && position"
    >
        <div
            v-for="(tags, i) in suggestionList"
            :key="i"
            :ref="
                (el) => {
                    if (i === selectedIndex) selectedElement = el as HTMLDivElement;
                }
            "
            :class="
                cn(
                    'w-full cursor-pointer truncate rounded p-1 text-sm hover:text-amber-600 hover:accent-amber-100',
                    i === selectedIndex ? 'bg-amber-200 text-accent-foreground' : '',
                )
            "
            @mousedown="fireAutocomplete(tags)"
        >
            {{ tags }}
        </div>
    </div>
</template>
