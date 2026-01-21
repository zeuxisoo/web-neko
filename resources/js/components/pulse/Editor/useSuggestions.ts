import getCaretCoordinates from 'textarea-caret';
import { computed, ref, watch, type Ref } from 'vue';
import type { EditorMethods, Position } from './types';

interface SuggestionOptions {
    editorRef: Ref<HTMLTextAreaElement | null>;
    editorMethods: EditorMethods;
    triggerChar: string;
    itemList: Ref<string[]>;
    filterList: (suggestionList: string[], searchWord: string) => string[];
    onSelectedItem: (item: string, word: string, startIndex: number) => void;
}

export default function useSuggestions(options: SuggestionOptions) {
    const isInstanced = ref(false);
    const selectedIndex = ref(0);
    const position = ref<Position | null>(null);
    const suggestionList = ref<string[]>([]);

    const isVisible = computed(() => {
        return !!(position.value && suggestionList.value.length > 0);
    });

    watch(
        () => options.editorRef.value,
        () => {
            registerListeners();
        },
    );

    const registerListeners = () => {
        if (!options.editorRef || isInstanced.value) {
            return;
        }

        const editor = options.editorRef;
        editor.value?.addEventListener('input', handleInput);
        editor.value?.addEventListener('keydown', handleKeyDown);
        editor.value?.addEventListener('blur', hide);

        isInstanced.value = true;
    };

    const handleInput = () => {
        if (!options.editorRef) {
            return;
        }

        const editor = options.editorRef;

        if (!editor.value) {
            return;
        }

        setSelectedIndex(0);
        const editorElement = editor.value;
        const [word, index] = getCurrentWord();
        const currentChar = editorElement.value[editorElement.selectionEnd];
        const isActive = word.startsWith(options.triggerChar) && currentChar !== options.triggerChar;

        // meet `trigger char` is active
        if (isActive) {
            const caretCordinates = getCaretCoordinates(editorElement, index);
            caretCordinates.top -= editorElement.scrollTop;
            setPosition(caretCordinates);

            const searchWord = getCurrentWord()[0].slice(options.triggerChar.length).toLowerCase();
            suggestionList.value = options.filterList(options.itemList.value, searchWord);
        } else {
            hide();
        }
    };

    const handleKeyDown = (e: KeyboardEvent) => {
        if (!isVisible.value) return;
        if (e.key === 'Escape') hide();

        const selected = selectedIndex.value;
        const tagsSuggestions = suggestionList.value;

        switch (e.key) {
            case 'ArrowDown':
                setSelectedIndex((selected + 1) % tagsSuggestions.length);
                e.preventDefault();
                e.stopPropagation();
                break;
            case 'ArrowUp':
                setSelectedIndex((selected - 1) % tagsSuggestions.length);
                e.preventDefault();
                e.stopPropagation();
                break;
            case 'Enter':
            case 'Tab':
                const item = tagsSuggestions[selected];

                if (item) {
                    fireAutocomplete(item);
                }

                e.preventDefault();
                e.stopPropagation();
                break;
        }
    };

    const setSelectedIndex = (index: number) => {
        selectedIndex.value = index;
    };

    const getCurrentWord = (): [word: string, startIndex: number] => {
        const editor = options.editorRef;

        if (!editor || !editor.value) {
            return ['', 0];
        }

        const editorElement = editor.value;
        const cursorPosition = editorElement.selectionEnd;

        const before = editorElement.value.slice(0, cursorPosition).match(/\S*$/) || { 0: '', index: cursorPosition };
        const after = editorElement.value.slice(cursorPosition).match(/^\S*/) || { 0: '' };

        return [before[0] + after[0], before.index ?? cursorPosition];
    };

    const setPosition = (caret: Position | null) => {
        position.value = caret;
    };

    const fireAutocomplete = (item: string) => {
        const [word, index] = getCurrentWord();
        options.onSelectedItem(item, word, index);
        hide();
    };

    const hide = () => setPosition(null);

    return {
        isVisible,
        position,
        selectedIndex,
        suggestionList,
        fireAutocomplete,
    };
}
