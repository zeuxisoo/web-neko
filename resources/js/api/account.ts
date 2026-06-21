import type {
    AccountProfileResponse,
    AccountProfileUpdateAvatarPayload,
    AccountProfileUpdatePayload,
    AccountSecurityResponse,
    AccountSecurityUpdatePasswordPayload,
} from './types/account';
import type { MeResponse } from './types/auth';
import useAgent from './useAgent';

class Profile {
    update(payload: AccountProfileUpdatePayload) {
        return useAgent<AccountProfileResponse>('account/profile/update').post(payload);
    }

    updateAvatar(payload: AccountProfileUpdateAvatarPayload) {
        return useAgent<MeResponse>('account/profile/upload/avatar').post(payload);
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
