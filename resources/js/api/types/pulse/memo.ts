import type { MeResponse } from '../auth';
import type { ApiResponse } from '../base';

export type PulseMemoStorePayload = {
    content: string;
    tag: string[];
    attachments: {
        id: number;
        filename: string;
        sort_order: number;
    }[];
};

export type PulseMemoUpdatePayload = {
    id: number;
    content: string;
    tags: string[];
    attachments: {
        id: number;
        filename: string;
        sort_order: number;
    }[];
};

export type PulseMemoIndexPayload = {
    page: number;
    tag?: string;
};

export interface PulseMemoStoreResponse extends ApiResponse {
    data: {
        id: number;
        user: MeResponse['data'];
        content: string;
        tags: {
            id: number;
            name: string;
            sort_order: number;
        }[];
        attachments: {
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
        links: {
            id: number;
            url: string;
            title: string;
            description: string;
            image: string;
            created_at: string;
        }[];
        is_bookmarked: boolean;
        comments_count: number;
        created_at: string;
    };
}

export interface PulseMemoIndexResponse extends ApiResponse {
    data: PulseMemoStoreResponse['data'][];
    links: {
        first: string;
        last: string;
        next: string;
        prev: string;
    };
    meta: {
        current_page: number;
        current_page_url: string;
        from: number;
        path: string;
        per_page: number;
        to: number;
    };
}

export interface PulseMemoDestroyResponse extends ApiResponse {
    data: string[];
}

export interface PulseMemoShowResponse extends ApiResponse {
    data: PulseMemoStoreResponse['data'];
}
