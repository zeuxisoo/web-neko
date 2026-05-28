import type { ApiResponse } from '../base';

export type PulseLinkStorePayload = {
    url: string;
    title: string;
    description: string;
    image: string;
};

export interface PulseLinkStoreResponse extends ApiResponse {
    data: {
        id: number;
        url: string;
        title: string;
        description: string;
        image: string;
        created_at: string;
    };
}

export interface PulseLinkDestroyResponse extends ApiResponse {
    data: string[];
}

export interface PulseLinkUnsavedResponse extends ApiResponse {
    data: PulseLinkStoreResponse['data'][];
}

export type PulseLinkIndexPayload = {
    page: number;
    keyword?: string;
};

export interface PulseLinkIndexResponse extends ApiResponse {
    data: PulseLinkStoreResponse['data'][];
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

export type PulseLinkFetchPayload = {
    url: string;
};

export interface PulseLinkFetchResponse extends ApiResponse {
    data: {
        title: string;
        description: string;
        url: string;
        image: string;
    };
}
