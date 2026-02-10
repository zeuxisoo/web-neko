import { createPinia } from 'pinia';
import useAttachmentsStore from './attachments';
import useAuthStore from './auth';
import useMemoStore from './memos';
import useTagStore from './tags';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAttachmentsStore, useAuthStore, useMemoStore, useTagStore, useUserStore };
