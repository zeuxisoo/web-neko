<script setup lang="ts">
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/base/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/base/tags-input';
import { useFilter } from 'reka-ui';
import { computed, ref } from 'vue';

const modelValue = defineModel({
    type: Array<string>,
    required: true,
});

const props = withDefaults(defineProps<{
    remoteTags: Array<{value: string, label: string}>,
}>(), {
    // remoteTags: () => [],
});

const emit = defineEmits([
    'update:modelValue',
]);

const openTagList = ref(false);
const searchTag = ref('');

const { contains } = useFilter({ sensitivity: 'base' });
const filteredTags = computed(() => {
    // filter out tags that are already selected in `modelValue`
    const unselectedRemoteTags = props.remoteTags.filter(tag =>
        !modelValue.value.includes(tag.label)
    );

    // if search term entered by the user
    // filter `unselectedRemoteTags` again, ensure the tags label only contains the search string.
    // otherwise, return all `unselectedRemoteTags` or `empty` list
    if (searchTag.value) {
        return unselectedRemoteTags.filter(tag => contains(tag.label, searchTag.value));
    } else {
        return unselectedRemoteTags;
    }
});
</script>

<template>
    <Combobox v-model="modelValue" v-model:open="openTagList" :ignore-filter="true" class="w-full">
        <ComboboxAnchor as-child class="w-full">
            <TagsInput v-model="modelValue" class="flex-1 gap-2 px-2">
                <div class="flex flex-wrap items-center gap-2">
                    <TagsInputItem v-for="item in modelValue" :key="item" :value="item" class="rounded-sm">
                        <TagsInputItemText />
                        <TagsInputItemDelete />
                    </TagsInputItem>
                </div>

                <ComboboxInput v-model="searchTag" as-child>
                    <TagsInputInput placeholder="Fruits..." class="h-auto w-full p-0" @keydown.enter.prevent />
                </ComboboxInput>
            </TagsInput>

            <ComboboxList class="w-[var(--reka-popper-anchor-width)]">
                <ComboboxEmpty />
                <ComboboxGroup>
                    <ComboboxItem
                        v-for="tag in filteredTags"
                        :key="tag.value"
                        :value="tag.label"
                        @select.prevent="
                            (ev: CustomEvent) => {
                                if (typeof ev.detail.value === 'string') {
                                    searchTag = '';
                                    modelValue.push(ev.detail.value);
                                }

                                if (filteredTags.length === 0) {
                                    openTagList = false;
                                }
                            }
                        "
                    >
                        {{ tag.label }}
                    </ComboboxItem>
                </ComboboxGroup>
            </ComboboxList>
        </ComboboxAnchor>
    </Combobox>
</template>
