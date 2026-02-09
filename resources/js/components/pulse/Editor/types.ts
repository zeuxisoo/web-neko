export type Attachment = PulseAttachmentUploadResponse['data'][number];
export type TagOrderedList = Record<string, number>;

export interface EditorMethods {
    insertText: (text: string, prefix?: string, suffix?: string) => void;
    removeText: (start: number, length: number) => void;
}

export type Position = {
    left: number;
    top: number;
    height: number;
};

export type SubmitData = {
    editor: string;
    attachments: Attachment[];
    tags: string[];
};
