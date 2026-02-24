import Validator from '@/validators/validator';

class SettingsAttachmentUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            max_size_kb: ['required', 'integer'],
            allowed_mimes: ['required', 'array'],
            max_files: ['required', 'integer', 'gte:1'],
            max_per_memo: ['required', 'integer', 'gte:1'],
        };
    }

    messages(): Record<string, string> {
        return {
            'max_size_kb.required': 'Please enter max size (kb)',
            'max_size_kb.integer': 'Max size (kb) must be integer',
            'allowed_mimes.required': 'Please enter allowed mime types',
            'allowed_mimes.array': 'Allowed mime types must be array',
            'max_files.required': 'Please enter max files number',
            'max_files.integer': 'Max file number must be integer',
            'max_files.gte': 'Max file number must greater than or equals 1',
            'max_per_memo.required': 'Please enter max per memo number',
            'max_per_memo.integer': 'Max per memo number must be integer',
            'max_per_memo.gte': 'Max per memo number must greater than or equals 1',
        };
    }
}

export default SettingsAttachmentUpdate;
