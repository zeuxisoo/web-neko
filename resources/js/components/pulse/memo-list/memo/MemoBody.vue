<script setup lang="ts">
import Badge from '@/components/base/badge/Badge.vue';
import MemoActionBookmark from '../memo-body/MemoActionBookmark.vue';
import MemoActionComment from '../memo-body/MemoActionComment.vue';
import MemoActionMore from '../memo-body/MemoActionMore.vue';
import MemoAttachment from '../memo-body/MemoAttachment.vue';
import MemoContent from '../memo-body/MemoContent.vue';

const props = withDefaults(
    defineProps<{
        memo: PulseMemoIndexResponse['data'][number];
        enableActionMore?: boolean;
        enableActionComment?: boolean;
    }>(),
    {
        enableActionMore: true,
        enableActionComment: true,
    },
);

const emit = defineEmits<{
    edit: [];
}>();

const handleEdit = () => {
    emit('edit');
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <MemoContent :content="props.memo.content" />
        <MemoAttachment :attachments="props.memo.attachments" />
        <div class="gap-2">
            <Badge variant="secondary" v-for="(tag, i) in props.memo.tags" :key="i"> #{{ tag.name }} </Badge>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <MemoActionComment :memo="props.memo" :enable-action-comment="props.enableActionComment" />
            <div class="bookmark flex justify-center">
                <MemoActionBookmark :memo="props.memo" />
            </div>
            <div class="action flex justify-end">
                <MemoActionMore :memo="props.memo" :enable-action-more="props.enableActionMore" @edit="handleEdit" />
            </div>
        </div>
    </div>
</template>
