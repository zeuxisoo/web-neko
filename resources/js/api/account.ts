import useAgent from './useAgent';

class Profile {
    update(payload: AccountProfileUpdatePayload) {
        return useAgent<AccountProfileResponse>('account/profile/update').post(payload);
    }
}

class Security {
    updatePassword(payload: AccountSecurityUpdatePasswordPayload) {
        return useAgent<AccountSecurityResponse>('account/security/update/password').post(payload);
    }
}

export default {
    get profile() {
        return new Profile();
    },

    get security() {
        return new Security();
    },
};
