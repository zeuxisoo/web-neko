import Validator from '@/validators/validator';

class DriftUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            id: ['required'],
            subject: ['required'],
            content: ['required'],
            tags: ['present'],
        };
    }

    messages(): Record<string, string> {
        return {
            'id.required': 'Please enter drift id',
            'subject.required': 'Please enter subject',
            'content.required': 'Please enter content',
            'tags.present': 'Tags must be present in form data',
        };
    }
}

export default DriftUpdate;
