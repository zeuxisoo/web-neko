<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { TagsInput } from '@/components/tags-input';
import { cn } from '@/lib/utils';
import { useTagsStore } from '@/stores';
import { Eye, Loader, X } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupTextarea } from '../base/input-group';

const props = withDefaults(
    defineProps<{
        isLoading?: boolean;
        enableCancel?: boolean;
        submitLabel?: string;
        drift?: DriftIndexResponse['data'][number] | null;
    }>(),
    {
        submitLabel: 'Submit',
    },
);

const emit = defineEmits<{
    submit: [DriftFromSubmitData];
    cancel: [];
}>();

const subject = ref('');
const content = ref('');
const selectedTags = ref<string[]>([]);

const tagsStore = useTagsStore();

const availableTags = computed(() => {
    return Object.keys(tagsStore.driftTags).map((name) => ({
        value: String(tagsStore.driftTags[name]),
        label: name,
    }));
});

onMounted(() => {
    tagsStore.fetchDrift();
});

watch(
    () => props.drift,
    (newDrift) => {
        if (newDrift) {
            subject.value = newDrift.subject;
            content.value = newDrift.content;
            selectedTags.value = newDrift.tags.map((tag) => tag.name);
        }
    },
    { immediate: true },
);

const handleSubmit = () => {
    emit('submit', {
        subject: subject.value,
        content: content.value,
        tags: selectedTags.value,
    });
};

const clearFormData = () => {
    subject.value = '';
    content.value = '';
    selectedTags.value = [];
};

defineExpose({
    clearFormData,
});
</script>

<template>
    <Card>
        <CardContent class="grid gap-4">
            <div class="grid gap-2">
                <Input v-model="subject" placeholder="Subject" />
            </div>
            <div class="grid gap-2">
                <InputGroup>
                    <InputGroupTextarea v-model="content" placeholder="What's on your mind?" />
                    <InputGroupAddon align="block-end">
                        <InputGroupButton variant="outline" class="ml-auto rounded-md" size="icon-xs">
                            <Eye />
                        </InputGroupButton>
                    </InputGroupAddon>
                </InputGroup>
            </div>
            <div class="grid gap-2">
                <TagsInput v-model="selectedTags" :remote-tags="availableTags" />
            </div>
            <div class="flex justify-end gap-1">
                <Button
                    v-if="props.enableCancel"
                    variant="secondary"
                    :class="cn('flex flex-row items-center gap-1 rounded-md', { 'disabled:': props.isLoading })"
                    @click="emit('cancel')"
                >
                    <X />
                </Button>
                <Button @click="handleSubmit" :disabled="props.isLoading">
                    <Loader v-if="props.isLoading" class="animate-spin" />
                    <template v-else>{{ props.submitLabel }}</template>
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
