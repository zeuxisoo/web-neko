type AuthStorageValue = {
    access_token: string;
    token_type: string;
    expires_in: number;
};

type Attachment = PulseAttachmentUploadResponse['data'][number];

type TagOrderedList = Record<string, number>;
