import {
    DriftIndexResponse,
    PulseAttachmentUploadResponse,
    PulseCommentIndexResponse,
    PulseLinkStoreResponse,
    PulseMemoIndexResponse,
} from '@/api/types';

export type AuthStorageValue = {
    access_token: string;
    token_type: string;
    expires_in: number;
};

export type Memo = PulseMemoIndexResponse['data'][number];
export type Attachment = PulseAttachmentUploadResponse['data'][number];
export type Link = PulseLinkStoreResponse['data'];
export type Comment = PulseCommentIndexResponse['data'][number];
export type Drift = DriftIndexResponse['data'][number];

export type TagOrderedList = Record<string, number>;
