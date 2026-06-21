import type { ApiResponse } from './base';

export type SettingsAttachmentUpdatePayload = {
    max_size_kb: number;
    allowed_mimes: string[];
    max_files: number;
    max_per_memo: number;
};

export interface SettingsAttachmentIndexResponse extends ApiResponse {
    data: {
        max_size_kb: number;
        allowed_mimes: string[];
        max_files: number;
        max_per_memo: number;
    };
}

export interface SettingsAttachmentUpdateResponse extends ApiResponse {
    data: SettingsAttachmentIndexResponse['data'];
}

export type SettingsPaginationUpdatePayload = {
    per_page_attachment: number;
    per_page_bookmark: number;
    per_page_comment: number;
    per_page_link: number;
    per_page_memo: number;
    per_page_drift: number;
};

export interface SettingsPaginationIndexResponse extends ApiResponse {
    data: {
        per_page_attachment: number;
        per_page_bookmark: number;
        per_page_comment: number;
        per_page_link: number;
        per_page_memo: number;
        per_page_drift: number;
    };
}

export interface SettingsPaginationUpdateResponse extends ApiResponse {
    data: SettingsPaginationIndexResponse['data'];
}

export interface SettingsIndexResponse extends ApiResponse {
    data: {
        attachment: SettingsAttachmentIndexResponse['data'];
        pagination: SettingsPaginationIndexResponse['data'];
    };
}

export interface SettingsClearResponse extends ApiResponse {
    data: [];
}
