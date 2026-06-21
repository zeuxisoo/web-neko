import api from '@/api';
import { MeResponse } from '@/api/types';
import { defineStore } from 'pinia';

type UserState = Omit<MeResponse['data'], 'isAdmin'> & { is_admin: boolean };

const useUserStore = defineStore('user', {
    state: (): UserState => ({
        id: 0,
        username: '',
        email: '',
        avatar: '',
        link_cover: '',
        link_thumb: '',
        is_admin: false,
    }),
    getters: {
        isAdmin: (state) => state.is_admin,
    },
    actions: {
        async fetch() {
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
        },
        setAvatar(filename: string, linkCover: string, linkThumb: string) {
            this.avatar = filename;
            this.link_cover = linkCover;
            this.link_thumb = linkThumb;
        },
    },
});

export default useUserStore;
