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

interface LogoutResponse extends ApiResponse {
    data: string[];
}

// account
type AccountProfileUpdatePayload = {
    username: string;
    email: string;
};

interface AccountProfileResponse extends ApiResponse {
    data: string[];
}

type AccountSecurityUpdatePasswordPayload = {
    old_password: string;
    new_password: string;
    new_password_confirmation: string;
};

interface AccountSecurityResponse extends ApiResponse {
    data: string[];
}
