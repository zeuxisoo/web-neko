import useAgent from './useAgent';

export default {
    login(payload: LoginPayload) {
        return useAgent<LoginResponse>('auth/login').post(payload);
    },

    me() {
        return useAgent<MeResponse>('auth/me').get();
    },

    logout() {
        return useAgent<LogoutResponse>('auth/logout').get();
    },
};
