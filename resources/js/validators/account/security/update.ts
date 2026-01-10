import Validator from '@/validators/validator';

class AccountSecurityUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            old_password: ['required'],
            new_password: ['required', 'min:8', 'confirmed'],
            new_password_confirmation: [],
        };
    }

    messages(): Record<string, string> {
        return {
            'old_password.required': 'Please enter old password',
            'new_password.required': 'Please enter new password',
            'new_password.min': 'New password letters must be more than %(args[0])s',
            'new_password.confirmed': 'New Password must be same as password confirmation',
        };
    }
}

export default AccountSecurityUpdate;
