import type { ApiResponse } from '../base';
import type { PulseMemoStoreResponse } from './memo';

export type PulseBookmarkIndexPayload = {
    page: number;
};

export interface PulseBookmarkIndexResponse extends ApiResponse {
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

export interface PulseBookmarkAddResponse extends ApiResponse {
    data: string[];
}

export interface PulseBookmarkRemoveResponse extends ApiResponse {
    data: string[];
}
