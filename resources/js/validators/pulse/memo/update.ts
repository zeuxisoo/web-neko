import Validator from '@/validators/validator';

class PulseMemoUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            id: ['required'],
            content: ['required'],
            tags: ['present'],
            attachments: ['present'],
            links: ['present'],
        };
    }

    messages(): Record<string, string> {
        return {
            'id.required': 'Please enter memo id',
            'content.required': 'Please enter memo content',
            'tags.present': 'Memo tags must be present in form data',
            'attachments.present': 'Memo attachments must be present in form data',
            'links.present': 'Memo links must be present in form data',
        };
    }
}

export default PulseMemoUpdate;
