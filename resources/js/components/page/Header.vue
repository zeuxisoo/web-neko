<script setup lang="ts">
import { VNode } from 'vue';
import { Card, CardContent } from '../base/card';

const props = defineProps<{
    title?: string;
}>();

defineSlots<{
    main(): VNode;
    title(): VNode;
}>();
</script>

<template>
    <Card class="py-3">
        <CardContent>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <slot name="main">
                        <h1 class="text-2xl font-semibold tracking-tight">
                            <slot name="title">
                                {{ props.title }}
                            </slot>
                        </h1>
                    </slot>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
