import type { ApiResponse } from './base';

export type AccountProfileUpdatePayload = {
    username: string;
    email: string;
    description: string;
};

export interface AccountProfileResponse extends ApiResponse {
    data: string[];
}

export type AccountProfileUpdateAvatarPayload = FormData;

export type AccountSecurityUpdatePasswordPayload = {
    old_password: string;
    new_password: string;
    new_password_confirmation: string;
};

export interface AccountSecurityResponse extends ApiResponse {
    data: string[];
}
