import { createPinia } from 'pinia';
import useAuthStore from './auth';
import useTagStore from './tag';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAuthStore, useTagStore, useUserStore };
