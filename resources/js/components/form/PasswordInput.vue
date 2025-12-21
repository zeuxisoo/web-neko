<script setup lang="ts">
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import { Eye, EyeOff } from 'lucide-vue-next';
import { computed, HTMLAttributes, ref } from 'vue';

// disable inherit attribute in first div element automatically
// manually add in input element
defineOptions({
    inheritAttrs: false,
});

const props = defineProps<{
    defaultValue?: string | number;
    modelValue?: string | number;
    class?: HTMLAttributes['class'];

    type: 'text' | 'password';
    enablePasswordToggle: Boolean;
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
});

//
const visible = ref(false);
const isPasswordType = props.type === 'password';
const inputType = computed(() => {
    if (isPasswordType && props.enablePasswordToggle) {
        return visible.value ? 'text' : 'password';
    }

    return props.type;
});
const toggleButtonIcon = computed(() => {
    return visible.value ? EyeOff : Eye;
});

const handleToggleButton = () => {
    console.log(visible.value);
    visible.value = !visible.value;
};
</script>

<template>
    <div className="relative w-full">
        <input
            v-model="modelValue"
            v-bind="$attrs"
            :type="inputType"
            :class="
                cn(
                    'flex h-9 w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none selection:bg-primary selection:text-primary-foreground file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm dark:bg-input/30',
                    'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                    'aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40',
                    props.class,
                )
            "
        />
        <button type="button" class="absolute inset-y-0 right-3 flex items-center text-muted-foreground" @click="handleToggleButton">
            <component :is="toggleButtonIcon" :size="18" />
        </button>
    </div>
</template>
