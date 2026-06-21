import type { MeResponse } from './auth';
import type { ApiResponse } from './base';

export type DriftStorePayload = {
    subject: string;
    content: string;
    tags?: string[];
};

export type DriftUpdatePayload = {
    id: number;
    subject: string;
    content: string;
    tags?: string[];
};

export type DriftIndexPayload = {
    page: number;
};

export interface DriftStoreResponse extends ApiResponse {
    data: {
        id: number;
        user: MeResponse['data'];
        subject: string;
        content: string;
        tags: {
            id: number;
            name: string;
            sort_order: number;
        }[];
        created_at: string;
    };
}

export interface DriftIndexResponse extends ApiResponse {
    data: DriftStoreResponse['data'][];
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

export interface DriftShowResponse extends ApiResponse {
    data: DriftStoreResponse['data'];
}

export interface DriftDestroyResponse extends ApiResponse {
    data: string[];
}
