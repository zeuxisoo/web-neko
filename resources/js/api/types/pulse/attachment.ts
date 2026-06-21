import type { ApiResponse } from '../base';

export type PulseAttachmentIndexPayload = {
    page: number;
    cursor?: number;
};

export interface PulseAttachmentUploadResponse extends ApiResponse {
    data: {
        id: number;
        filename: string;
        original_name: string;
        mime_type: string;
        size: number;
        sort_order: number;
        year: number;
        month: number;
        created_at: string;
        links: Record<'cover' | 'thumb', string>;
    }[];
}

export interface PulseAttachmentUnsavedResponse extends PulseAttachmentUploadResponse {}

export interface PulseAttachmentIndexResponse extends PulseAttachmentUploadResponse {
    links: {
        next: string | null;
        prev: string | null;
    };
    meta: {
        current_page: number | null;
        per_page: number;
        first_year: number;
        last_year: number;
    };
}

export interface PulseAttachmentDestroyResponse extends ApiResponse {
    data: string[];
}
