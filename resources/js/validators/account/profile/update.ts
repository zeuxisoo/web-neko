import Validator from '@/validators/validator';

class AccountProfileUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            username: ['required', 'min:4'],
            email: ['required', 'email'],
        };
    }

    messages(): Record<string, string> {
        return {
            'username.required': 'Please enter account',
            'username.min': 'Username letters must be more than %(args[0])s',
            'email.required': 'Please enter email',
            'email.email': 'Invalid email format',
        };
    }
}

export default AccountProfileUpdate;
