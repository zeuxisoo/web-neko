import { createPinia } from 'pinia';
import useAttachmentStore from './attachment';
import useAuthStore from './auth';
import useTagStore from './tag';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAttachmentStore, useAuthStore, useTagStore, useUserStore };
