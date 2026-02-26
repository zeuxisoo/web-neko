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
        avatar: string;
        link: string;
        isAdmin: boolean;
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

type AccountProfileUpdateAvatarPayload = FormData;

type AccountSecurityUpdatePasswordPayload = {
    old_password: string;
    new_password: string;
    new_password_confirmation: string;
};

interface AccountSecurityResponse extends ApiResponse {
    data: string[];
}

// pulse memo
type PulseMemoStorePayload = {
    content: string;
    tag: string[];
    attachments: {
        id: number;
        filename: string;
        sort_order: number;
    }[];
};

type PulseMemoUpdatePayload = {
    id: number;
    content: string;
    tags: string[];
    attachments: {
        id: number;
        filename: string;
        sort_order: number;
    }[];
};

type PulseMemoIndexPayload = {
    page: number;
    tag?: string;
};

interface PulseMemoStoreResponse extends ApiResponse {
    data: {
        id: number;
        user: MeResponse['data'];
        content: string;
        tags: {
            id: number;
            name: string;
            sort_order: number;
        }[];
        attachments: {
            id: number;
            filename: string;
            original_name: string;
            mime_type: string;
            size: number;
            sort_order: number;
            links: Record<'cover' | 'thumb', string>;
            created_at: string;
        }[];
        links: {
            id: number;
            url: string;
            title: string;
            description: string;
            image: string;
            created_at: string;
        }[];
        is_bookmarked: boolean;
        comments_count: number;
        created_at: string;
    };
}

interface PulseMemoIndexResponse extends ApiResponse {
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

interface PulseMemoDestroyResponse extends ApiResponse {
    data: string[];
}

interface PulseMemoShowResponse extends ApiResponse {
    data: PulseMemoStoreResponse['data'];
}

// pulse attachment
type PulseAttachmentIndexPayload = {
    page: number;
    cursor?: number;
};

interface PulseAttachmentUploadResponse extends ApiResponse {
    data: {
        id: number;
        filename: string;
        original_name: string;
        mime_type: string;
        size: number;
        sort_order: number;
        created_at: string;
        links: Record<'cover' | 'thumb', string>;
    }[];
}

interface PulseAttachmentUnsavedResponse extends PulseAttachmentUploadResponse {}
interface PulseAttachmentIndexResponse extends PulseAttachmentUploadResponse {
    links: {
        next: string | null;
        prev: string | null;
    };
    meta: {
        current_page: number | null;
        per_page: number;
        first_year: number;
        last_year: number;
    };
}

interface PulseAttachmentDestroyResponse extends ApiResponse {
    data: string[];
}

interface PulseBookmarkAddResponse extends ApiResponse {
    data: string[];
}

// pulse bookmark
interface PulseBookmarkRemoveResponse extends ApiResponse {
    data: string[];
}

// pulse tag
interface PulseTagResponse extends ApiResponse {
    data: {
        id: number;
        name: string;
        order_column: number;
    }[];
}

// pulse link
type PulseLinkStorePayload = {
    url: string;
    title: string;
    description: string;
    image: string;
};

interface PulseLinkStoreResponse extends ApiResponse {
    data: {
        id: number;
        url: string;
        title: string;
        description: string;
        image: string;
        created_at: string;
    };
}

interface PulseLinkDestroyResponse extends ApiResponse {
    data: string[];
}

interface PulseLinkUnsavedResponse extends ApiResponse {
    data: PulseLinkStoreResponse['data'][];
}

type PulseLinkFetchPayload = {
    url: string;
};

interface PulseLinkFetchResponse extends ApiResponse {
    data: {
        title: string;
        description: string;
        url: string;
        image: string;
        extra: {
            site_name: string;
            image_attribute: {
                width: number;
                height: number;
                alt: string;
                type: string;
            };
        };
    };
}

// pulse comment
type PulseCommentStorePayload = {
    memo_id: number;
    content: string;
};

type PulseCommentIndexPayload = {
    memo_id: number;
    page: number;
};

interface PulseCommentIndexResponse extends ApiResponse {
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

interface PulseCommentStoreResponse extends ApiResponse {
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

// settings
type SettingsAttachmentUpdatePayload = {
    max_size_kb: number;
    allowed_mimes: string[];
    max_files: number;
    max_per_memo: number;
};

interface SettingsAttachmentIndexResponse extends ApiResponse {
    data: {
        max_size_kb: number;
        allowed_mimes: string[];
        max_files: number;
        max_per_memo: number;
    };
}

interface SettingsAttachmentUpdateResponse extends ApiResponse {
    data: SettingsAttachmentIndexResponse['data'];
}
