<script setup lang="ts">
import { PulseMemoIndexResponse } from '@/api/types';
import { PaperclipIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import LightBox, { LightBoxComponent } from 'vue-it-bigger';
import MemoPreviewImage from './MemoPreviewImage.vue';

const props = defineProps<{
    attachments: PulseMemoIndexResponse['data'][number]['attachments'];
}>();

const showLightBox = ref(false);
const lightBoxRef = ref<LightBoxComponent>();

// collect all attachments for lightbox
const lightboxAttachments = computed(() => {
    return props.attachments.map((attachment) => ({
        type: 'image',
        src: attachment.links.thumb,
        thumb: attachment.links.cover,
        caption: attachment.original_name,
    }));
});

const handleShowLightBox = (index: number) => {
    if (lightBoxRef.value) {
        lightBoxRef.value.showImage(index);
    }
};
</script>

<template>
    <div class="boder-boder flex flex-col gap-1 rounded-sm border" v-if="props.attachments.length > 0">
        <div className="flex items-center gap-1 p-2 border-b border-border bg-muted/30 text-muted-foreground">
            <PaperclipIcon :size="12" />
            <span class="text-xs">Attachments ({{ props.attachments.length }})</span>
        </div>
        <div class="grid grid-cols-2 justify-items-center gap-2 p-1 md:grid-cols-6">
            <MemoPreviewImage
                v-for="(attachment, i) in props.attachments"
                :key="i"
                :index="i"
                :image="{ src: attachment.links.thumb, title: attachment.original_name }"
                @click="handleShowLightBox"
            />
        </div>
    </div>
    <LightBox ref="lightBoxRef" :media="lightboxAttachments" :showLightBox="showLightBox" :interfaceHideTime="86400" :showCaption="true" />
</template>
