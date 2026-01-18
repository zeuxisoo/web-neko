import api from '@/api';
import { defineStore } from 'pinia';

const avatarUrlPrefix = '/storage/avatar/';

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

                    me.avatar = avatarUrlPrefix + me.avatar;

                    this.$patch(me);

                    return me;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                throw e;
            }
        },
        setAvatar(filename: string) {
            this.avatar = avatarUrlPrefix + filename;
        },
    },
});

export default useUserStore;
