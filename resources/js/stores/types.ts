type AuthStorageValue = {
    access_token: string;
    token_type: string;
    expires_in: number;
};

type Memo = PulseMemoIndexResponse['data'][number];
type Attachment = PulseAttachmentUploadResponse['data'][number];
type Comment_ = PulseCommentIndexResponse['data'][number];

type TagOrderedList = Record<string, number>;
