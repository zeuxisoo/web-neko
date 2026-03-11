<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Textarea } from '@/components/base/textarea';
import { TagsInput } from '@/components/tags-input';
import { useTagsStore } from '@/stores';
import { Loader } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    isLoading?: boolean;
    drift?: DriftIndexResponse['data'][number] | null;
}>();

const emit = defineEmits<{
    submit: [DriftFromSubmitData];
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

    subject.value = '';
    content.value = '';
    selectedTags.value = [];
};
</script>

<template>
    <Card>
        <CardContent class="grid gap-4">
            <div class="grid gap-2">
                <Input v-model="subject" placeholder="Subject" />
            </div>
            <div class="grid gap-2">
                <Textarea v-model="content" placeholder="What's on your mind?" />
            </div>
            <div class="grid gap-2">
                <TagsInput v-model="selectedTags" :remote-tags="availableTags" />
            </div>
            <div class="flex justify-end">
                <Button @click="handleSubmit" :disabled="props.isLoading">
                    <Loader v-if="props.isLoading" class="animate-spin" />
                    <template v-else>Post</template>
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
