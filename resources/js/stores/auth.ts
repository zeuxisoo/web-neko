import { useAuthStorage } from '@/composables';
import { defineStore } from 'pinia';

// create empty auth state if not init
const authDefaultValue = {} as AuthStorageValue;
const authStorage = useAuthStorage(authDefaultValue);

// default auth state for store
const storeStateAuthValue: AuthStorageValue = {
    access_token: '',
    token_type: '',
    expires_in: 0,
};

const useAuthStore = defineStore('auth', {
    state: () => ({
        auth: storeStateAuthValue,
        isLoggedIn: false,
    }),
    actions: {
        activateAuth(auth: AuthStorageValue) {
            authStorage.value = auth;

            this.auth = auth;
            this.isLoggedIn = true;
        },
    },
});

export default useAuthStore;
