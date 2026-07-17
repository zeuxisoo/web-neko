<script setup lang="ts">
import { cn } from '@/lib/utils';
import { CustomAttrs, VueMarkdown } from '@crazydos/vue-markdown';
import { useClipboard, useDark } from '@vueuse/core';
import { ChevronsUpDown, Copy, Download, Eye, FileBraces } from 'lucide-vue-next';
import rehypeRaw from 'rehype-raw';
import rehypeSanitize from 'rehype-sanitize';
import remarkBreaks from 'remark-breaks';
import remarkGfm from 'remark-gfm';
import { getSingletonHighlighterCore, type HighlighterCore } from 'shiki/core';
import { createJavaScriptRegexEngine } from 'shiki/engine/javascript';
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import { Button } from '../base/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '../base/collapsible';

const props = defineProps<{
    content: string;
    disableEyeButton?: boolean;
}>();

const emit = defineEmits<{
    close: [];
}>();

const isDark = useDark();
const highlighterInstance = ref<HighlighterCore | null>(null);
const clipboard = useClipboard({ source: ref('') });

const remarkPlugins = [remarkGfm, remarkBreaks];
const rehypePlugins = [rehypeRaw, rehypeSanitize];

const customAttrs = ref<CustomAttrs>({
    heading: (node, combinedAttrs) => {
        const level = combinedAttrs.level as number;
        const levelClasses: { [key: number]: string } = {
            1: 'text-3xl font-bold border-b pb-2',
            2: 'text-2xl font-semibold border-b pb-1.5',
            3: 'text-xl font-semibold',
            4: 'text-lg font-semibold',
            5: 'text-base font-semibold',
            6: 'text-base font-medium text-accent-foreground',
        };

        return {
            class: cn('mt-1.5 mb-2 leading-tight', levelClasses[level]),
        };
    },
    a: {
        class: ['text-primary underline decoration-primary/50 hover:text-primary/65 hover:decoration-primary'],
        target: '_blank',
        rel: 'noopener noreferrer',
    },
    p: {
        class: ['my-0 my-2 leading-6'],
    },
    blockquote: {
        class: ['my-0 my-2 border-l-3 border-primary/30 pl-3 text-muted-foreground font-medium italic'],
    },
    hr: {
        class: ['my-2 h-0 border-1 border-b border-dashed'],
    },
    list: (node, combinedAttrs) => {
        const ordered = combinedAttrs.ordered as boolean;
        const classNames = (node.properties.className ?? []) as string[];
        const isTaskList = classNames.includes('contains-task-list');

        // `&>p` selector for nested task list
        const listStyle = isTaskList ? 'list-none [&_ul.contains-task-list]:ml-4' : cn('pl-6', ordered ? 'list-decimal' : 'list-disc');

        return {
            class: cn('m-2 my-0 list-outside', listStyle, ...classNames),
        };
    },
    'list-item': (node, combinedAttrs) => {
        const classNames = (node.properties.className ?? []) as string[];
        const isTaskListItem = classNames.includes('task-list-item');

        const listItemStyle = isTaskListItem ? 'list-none [&>p]:inline [&>p]:m-0' : '';

        return {
            class: cn('mt-0.5 leading-6', listItemStyle, classNames),
        };
    },
    thead: {
        class: 'border-b bg-muted/50',
    },
    tbody: {
        class: 'divide-y',
    },
    th: {
        class: 'px-2 py-1 text-left align-middle font-medium text-muted-foreground',
    },
    tr: {
        class: 'transition-colors hover:bg-accent/25',
    },
    td: {
        class: 'px-2 py-1 text-left align-middle',
    },
    'inline-code': {
        // `code` tag
        class: 'font-mono text-sm bg-muted px-1 py-0.5 rounded-md hover:bg-muted-foreground/20',
    },
});

const handleDownload = (language: string, content: string) => {
    const blob = new Blob([content], { type: 'text/plain' });

    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.rel = 'noopener';
    link.download = `code.${language}.txt`;

    try {
        link.dispatchEvent(new MouseEvent('click'));
    } catch (e: unknown) {
        toast.error(`Cannot create download file: ${link.download}`);
    }
};

const handleCopy = (language: string, content: string) => {
    clipboard.copy(content);

    toast.info(`Language "${language}" copied`);
};

onMounted(async () => {
    // createHighlighter({})
    const highlighter = await getSingletonHighlighterCore({
        themes: [import('@shikijs/themes/github-light'), import('@shikijs/themes/github-dark')],
        langs: [
            import('@shikijs/langs/bash'),
            import('@shikijs/langs/php'),
            import('@shikijs/langs/python'),
            import('@shikijs/langs/javascript'),
            import('@shikijs/langs/typescript'),
            import('@shikijs/langs/go'),
            import('@shikijs/langs/v'),
        ],
        engine: createJavaScriptRegexEngine(),
    });

    highlighterInstance.value = highlighter;
});

const highlightCode = (content: string, language: string): string => {
    if (!highlighterInstance.value) {
        return content;
    }

    return highlighterInstance.value.codeToHtml(content, {
        theme: isDark.value ? 'github-dark' : 'github-light',
        lang: language,
    });
};
</script>

<template>
    <div class="rounded-md border-none p-0">
        <VueMarkdown :markdown="props.content" :remarkPlugins="remarkPlugins" :rehypePlugins="rehypePlugins" :customAttrs="customAttrs">
            <template #table="{ children, ...props }">
                <div class="my-3 w-full overflow-x-auto rounded-md border bg-muted/20">
                    <table class="w-full border-collapse">
                        <Component :is="children" />
                    </table>
                </div>
            </template>
            <template #block-code="{ children, ...props }">
                <div class="block-code rounded-md border">
                    <Collapsible :default-open="true">
                        <div class="flex justify-between bg-accent p-1.5">
                            <div class="flex items-center gap-1 font-semibold uppercase"><FileBraces :size="14" />{{ props.language }}</div>
                            <div class="gap-0.5">
                                <Button variant="ghost" size="icon-sm" class="size-7" @click="handleDownload(props.language, props.content)">
                                    <Download :size="10" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="icon-sm"
                                    class="size-7"
                                    v-if="clipboard.isSupported"
                                    @click="handleCopy(props.language, props.content)"
                                >
                                    <Copy :size="10" />
                                </Button>
                                <CollapsibleTrigger as-child>
                                    <Button variant="ghost" size="icon-sm" class="size-7">
                                        <ChevronsUpDown :size="10" />
                                    </Button>
                                </CollapsibleTrigger>
                            </div>
                        </div>
                        <CollapsibleContent>
                            <div class="text-sm [&>pre]:rounded-md [&>pre]:p-1.5" v-html="highlightCode(props.content, props.language)"></div>
                        </CollapsibleContent>
                    </Collapsible>
                </div>
            </template>
            <template #img="{ children, ...props }">
                <div class="flex justify-center">
                    <img class="h-auto max-w-full rounded-md" v-bind="props" />
                </div>
            </template>
        </VueMarkdown>
        <div class="flex justify-end" v-if="!props.disableEyeButton">
            <Button variant="outline" class="rounded-md" size="icon-sm" @click="emit('close')">
                <Eye />
            </Button>
        </div>
    </div>
</template>
