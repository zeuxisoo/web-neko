import { useAuthStorage } from '@/composables';
import { isEmpty } from 'es-toolkit/compat';
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
    getters: {
        isAuthenticated(state): boolean {
            return state.isLoggedIn === true && state.auth.access_token.length > 0 && state.auth.token_type.length > 0 && state.auth.expires_in !== 0;
        },
    },
    actions: {
        activateAuth(auth: AuthStorageValue) {
            authStorage.value = auth;

            this.auth = auth;
            this.isLoggedIn = true;
        },
        restoreAuth() {
            const authValue = authStorage.value;
            if (!isEmpty(authValue)) {
                this.activateAuth(authValue);
            }
        },
        deactivateAuth() {
            authStorage.value = authDefaultValue;

            this.auth = storeStateAuthValue;
            this.isLoggedIn = false;
        },
    },
});

export default useAuthStore;
