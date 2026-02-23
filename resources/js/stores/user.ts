import api from '@/api';
import { defineStore } from 'pinia';

const useUserStore = defineStore('user', {
    state: () => ({
        id: 0,
        username: '',
        email: '',
        avatar: '',
        link: '',
        isAdmin: false,
    }),
    getters: {
        isAdmin: (state) => state.isAdmin,
    },
    actions: {
        async fetch() {
            try {
                const { data, error } = await api.auth.me().json<MeResponse>();

                if (error.value) {
                    throw error.value;
                }

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
        setAvatar(filename: string, link: string) {
            this.avatar = filename;
            this.link = link;
        },
    },
});

export default useUserStore;
