<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Textarea } from '@/components/base/textarea';
import TagsInput from '@/components/tags-input/TagsInput.vue';
import ImageDialog from '@/components/upload-dialog/ImageDialog.vue';
import { SendHorizontal } from 'lucide-vue-next';
import { ref } from 'vue';

const remoteTags = [
    { value: 'apple', label: 'Apple' },
    { value: 'banana', label: 'Banana' },
    { value: 'cherry', label: 'Cherry' },
    { value: 'durian', label: 'Durian' },
];

const currentTags = ref<string[]>([]);

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
                        <TagsInput v-model="currentTags" :remote-tags="remoteTags"  />
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <ImageDialog />
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
