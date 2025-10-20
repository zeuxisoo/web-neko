<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/base/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/base/tags-input';
import { Textarea } from '@/components/base/textarea';
import { ImageUp, SendHorizontal } from 'lucide-vue-next';
import { useFilter } from 'reka-ui';
import { computed, ref } from 'vue';

const remoteTags = [
    { value: 'apple', label: 'Apple' },
    { value: 'banana', label: 'Banana' },
    { value: 'cherry', label: 'Cherry' },
    { value: 'durian', label: 'Durian' },
];

const currentTags = ref<string[]>([]);
const openTagList = ref(false);
const searchTag = ref('');

const { contains } = useFilter({ sensitivity: 'base' });
const filteredTags = computed(() => {
    // filter out tags that are already selected in currentTags
    const unselectedRemoteTags = remoteTags.filter(tag =>
        !currentTags.value.includes(tag.label)
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

const submit = () => {
    console.log(currentTags.value);
};
</script>

<template>
    <div class="pulse">
        <Card>
            <CardContent>
                <div class="item-center grid w-full gap-4">
                    <div class="flex flex-col">
                        <Textarea name="content" class="min-h-[100px]" placeholder="Place whatever you want" />
                    </div>
                    <div class="flex w-full items-center">
                        <Combobox v-model="currentTags" v-model:open="openTagList" :ignore-filter="true" class="w-full">
                            <ComboboxAnchor as-child class="w-full">
                                <TagsInput v-model="currentTags" class="flex-1 gap-2 px-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <TagsInputItem v-for="item in currentTags" :key="item" :value="item" class="rounded-sm">
                                            <TagsInputItemText />
                                            <TagsInputItemDelete />
                                        </TagsInputItem>
                                    </div>

                                    <ComboboxInput v-model="searchTag" as-child>
                                        <TagsInputInput
                                            placeholder="Fruits..."
                                            class="h-auto w-full p-0"
                                            @keydown.enter.prevent
                                        />
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
                                                        currentTags.push(ev.detail.value);
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
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <Button>
                                <ImageUp />
                            </Button>
                        </div>
                        <Button @click="submit">
                            <SendHorizontal /> Submit
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
