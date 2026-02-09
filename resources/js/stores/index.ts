import { createPinia } from 'pinia';
import useAttachmentStore from './attachments';
import useAuthStore from './auth';
import useMemoStore from './memos';
import useTagStore from './tags';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAttachmentStore, useAuthStore, useMemoStore, useTagStore, useUserStore };
