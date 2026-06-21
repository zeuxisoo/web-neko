import type { MeResponse } from '../auth';
import type { ApiResponse } from '../base';

export type PulseCommentStorePayload = {
    memo_id: number;
    content: string;
};

export type PulseCommentIndexPayload = {
    memo_id: number;
    page: number;
};

export interface PulseCommentIndexResponse extends ApiResponse {
    data: PulseCommentStoreResponse['data'][];
    links: {
        first: string;
        last: string;
        next: string | null;
        prev: string | null;
    };
    meta: {
        current_page: number;
        current_page_url: string;
        from: number | null;
        path: string;
        per_page: number;
        to: number | null;
    };
}

export interface PulseCommentStoreResponse extends ApiResponse {
    data: {
        id: number;
        user_id: number;
        memo_id: number;
        memo_comment_id: number | null;
        content: string;
        created_at: string;
        user: MeResponse['data'];
    };
}
