<script setup lang="ts">
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import type { ComboboxInputEmits, ComboboxInputProps } from 'reka-ui';
import { ComboboxInput, useForwardPropsEmits } from 'reka-ui';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps<
    ComboboxInputProps & {
        class?: HTMLAttributes['class'];
    }
>();

const emits = defineEmits<ComboboxInputEmits>();

const delegatedProps = reactiveOmit(props, 'class');

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <div data-slot="command-input-wrapper" class="flex flex-1 items-center">
        <ComboboxInput
            data-slot="command-input"
            :class="
                cn(
                    'flex w-full rounded-md bg-transparent text-sm outline-hidden placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50',
                    props.class,
                )
            "
            v-bind="{ ...forwarded, ...$attrs }"
        >
            <slot />
        </ComboboxInput>
    </div>
</template>
