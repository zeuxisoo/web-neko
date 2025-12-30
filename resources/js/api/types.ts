// base
interface ApiResponse {
    ok: boolean;
    message: string;
}

// auth
type LoginPayload = {
    account: string;
    password: string;
};

interface LoginResponse extends ApiResponse {
    data: {
        access_token: string;
        expires_in: number;
        token_type: string;
    };
}

interface MeResponse extends ApiResponse {
    data: {
        id: number;
        username: string;
        email: string;
    };
}
