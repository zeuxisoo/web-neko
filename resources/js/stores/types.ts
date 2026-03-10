type AuthStorageValue = {
    access_token: string;
    token_type: string;
    expires_in: number;
};

type Memo = PulseMemoIndexResponse['data'][number];
type Attachment = PulseAttachmentUploadResponse['data'][number];
type Link = PulseLinkStoreResponse['data'];
type Comment_ = PulseCommentIndexResponse['data'][number];
type Drift = DriftIndexResponse['data'][number];

type TagOrderedList = Record<string, number>;
