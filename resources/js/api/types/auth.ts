import type { ApiResponse } from './base';

// auth
export type LoginPayload = {
    account: string;
    password: string;
};

export interface LoginResponse extends ApiResponse {
    data: {
        access_token: string;
        expires_in: number;
        token_type: string;
    };
}

export interface MeResponse extends ApiResponse {
    data: {
        id: number;
        username: string;
        email: string;
        description: string;
        avatar: string;
        link_cover: string;
        link_thumb: string;
        isAdmin: boolean;
    };
}

export interface LogoutResponse extends ApiResponse {
    data: string[];
}

// re-export user type for convenience
export type User = MeResponse['data'];
