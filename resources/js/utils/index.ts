export { default as extractHashTags } from './hashtags';
export { default as WhoopsHandler } from './whoops';
import { Attachment } from '@/components/pulse/editor/types';
import { format, formatDistanceToNow, parseISO } from 'date-fns';

const humanSize = (bytes: number) => {
    if (bytes === 0) {
        return '0 Bytes';
    }

    const units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));

    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + units[i];
};

const fileSubType = (mimeType: string, upperCase: boolean = true) => {
    const [kind, subType] = mimeType.split('/', 2);

    if (upperCase) {
        return subType.toUpperCase();
    }

    return subType;
};

const fillAttachments = (attachments: Attachment[], attachment: PulseAttachmentUploadResponse['data'][number]) => {
    attachments.push({
        id: attachment.id,
        filename: attachment.filename,
        original_name: attachment.original_name,
        mime_type: attachment.mime_type || 'application/octet-stream',
        size: attachment.size,
        sort_order: attachment.sort_order,
        year: attachment.year,
        month: attachment.month,
        created_at: attachment.created_at,
        links: attachment.links,
    });
};

const humanDateTime = (datetime: string, raw: boolean = false, tense: '12hr' | '24hr' = '12hr') => {
    if (raw) {
        return format(datetime, tense === '12hr' ? 'yyyy/MM/dd hh:mm a' : 'yyyy/MM/dd HH:mm');
    }

    const parsedDate = parseISO(datetime);
    const distance = formatDistanceToNow(parsedDate);

    return distance;
};

export { fileSubType, fillAttachments, humanDateTime, humanSize };
