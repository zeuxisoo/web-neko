import Validator from '@/validators/validator';

class DriftStore extends Validator {
    rules(): Record<string, string[]> {
        return {
            subject: ['required'],
            content: ['required'],
            tags: ['present'],
        };
    }

    messages(): Record<string, string> {
        return {
            'subject.required': 'Please enter subject',
            'content.required': 'Please enter content',
            'tags.present': 'Tags must be present in form data',
        };
    }
}

export default DriftStore;
