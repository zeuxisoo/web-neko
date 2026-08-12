import Validator from '@/validators/validator';

class AccountProfileDetailUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            description: ['required'],
        };
    }

    messages(): Record<string, string> {
        return {
            'description.required': 'Please enter description',
        };
    }
}

export default AccountProfileDetailUpdate;
