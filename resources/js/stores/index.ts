import { createPinia } from 'pinia';
import useAuthStore from './auth';
import useUserStore from './user';

const pinia = createPinia();

export default pinia;
export { useAuthStore, useUserStore };
