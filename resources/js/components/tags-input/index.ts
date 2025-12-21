/*
import { TagsInput } from '@/components/tags-input';

const currentTags = ref<string[]>([]);
const remoteTags = [
    { value: 'apple', label: 'Apple' },
    { value: 'banana', label: 'Banana' },
    { value: 'cherry', label: 'Cherry' },
    { value: 'durian', label: 'Durian' },
];

<TagsInput v-model="currentTags" :remote-tags="remoteTags"  />
*/

export { default as TagsInput } from './TagsInput.vue';
