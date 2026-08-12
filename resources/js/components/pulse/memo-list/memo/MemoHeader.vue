<script setup lang="ts">
import { MeResponse } from '@/api/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import { humanDateTime } from '@/utils';
import { computed, ref } from 'vue';
import LightBox, { LightBoxComponent } from 'vue-it-bigger';

const props = defineProps<{
    item: {
        user: MeResponse['data'];
        created_at: string;
    };
}>();

const showRawDateTime = ref(false);
const showLightBox = ref(false);
const lightBoxRef = ref<LightBoxComponent>();

const lightboxMedia = computed(() => [
    {
        type: 'image',
        src: props.item.user.link_thumb,
        thumb: props.item.user.link_thumb,
        caption: props.item.user.description,
    },
]);

const handleShowLightBox = (index: number) => {
    if (!props.item.user.avatar) {
        return;
    }

    if (lightBoxRef.value) {
        lightBoxRef.value.showImage(index);
    }
};
</script>

<template>
    <div class="flex items-center gap-2 text-left text-sm">
        <Avatar class="h-12 w-12 cursor-pointer rounded-lg" @click="handleShowLightBox(0)">
            <AvatarImage :src="props.item.user.link_cover" :alt="props.item.user.username" />
            <AvatarFallback class="rounded-lg">{{ props.item.user.username.slice(0, 2).toUpperCase() }}</AvatarFallback>
        </Avatar>
        <div class="grid flex-1 text-left text-sm leading-6">
            <span class="truncate font-semibold">{{ props.item.user.username }}</span>
            <span class="flex items-center text-xs text-accent-foreground/80" @click="showRawDateTime = !showRawDateTime">
                {{ humanDateTime(props.item.created_at, showRawDateTime) }}
            </span>
        </div>
    </div>
    <LightBox ref="lightBoxRef" :media="lightboxMedia" :showLightBox="showLightBox" :interfaceHideTime="86400" :showCaption="true" />
</template>
