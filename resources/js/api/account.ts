import useAgent from './useAgent';

class Profile {
    update(payload: AccountProfileUpdatePayload) {
        return useAgent<AccountProfileResponse>('account/profile/update').post(payload);
    }
}

export default {
    get profile() {
        return new Profile();
    },
};
