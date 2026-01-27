export interface EditorMethods {
    insertText: (text: string, prefix?: string, suffix?: string) => void;
    removeText: (start: number, length: number) => void;
}

export type Position = {
    left: number;
    top: number;
    height: number;
};

export type TagOrderedList = Record<string, number>;

export type SubmitData = {
    editor: string;
    attachments: Attachment[];
};

export interface Attachment {
    id: number;
    filename: string;
    original_name: string;
    size: number;
    type: string;
    links: Record<'cover' | 'thumb', string>;
}
