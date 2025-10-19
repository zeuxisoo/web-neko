import App from '@/views/App.vue';
import { createApp } from 'vue';
import router from './routes';

createApp(App)
    .use(router)
    .mount('#neko');
