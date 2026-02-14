import { createPinia } from 'pinia';
import useAttachmentsStore from './attachments';
import useAuthStore from './auth';
import useCommentsStore from './comments';
import useMemosStore from './memos';
import useTagsStore from './tags';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAttachmentsStore, useAuthStore, useCommentsStore, useMemosStore, useTagsStore, useUserStore };
