import Validator from '@/validators/validator';

class PulseMemoStore extends Validator {
    rules(): Record<string, string[]> {
        return {
            content: ['required'],
            tags: ['present'],
            attachments: ['present'],
        };
    }

    messages(): Record<string, string> {
        return {
            'content.required': 'Please enter content',
            'tags.present': 'Tags must be present in form data',
            'attachments.present': 'Attachments must be present in form data',
        };
    }
}

export default PulseMemoStore;
