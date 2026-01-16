import api from '@/api';
import { defineStore } from 'pinia';

const useUserStore = defineStore('user', {
    state: () => ({
        id: 0,
        username: '',
        email: '',
        avatar: '',
    }),
    actions: {
        async fetch() {
            try {
                const { data, error } = await api.auth.me().json<MeResponse>();

                if (data && data.value) {
                    const result = data.value;
                    const me = result.data;

                    this.$patch(me);

                    return me;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                throw e;
            }
        },
        setAvatar(url: string) {
            this.avatar = url;
        },
    },
});

export default useUserStore;
