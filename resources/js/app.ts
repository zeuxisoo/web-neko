import App from '@/views/App.vue';
import { createApp } from 'vue';
import router from './routes';
import pinia from './stores';

// prettier-ignore
createApp(App)
    .use(pinia)
    .use(router)
    .mount('#neko');
