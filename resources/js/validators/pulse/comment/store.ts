import Validator from '@/validators/validator';

class PulseCommentStore extends Validator {
    rules(): Record<string, string[]> {
        return {
            memo_id: ['required'],
            content: ['required'],
        };
    }

    messages(): Record<string, string> {
        return {
            'memo_id.required': 'Please select a memo',
            'content.required': 'Please enter content',
        };
    }
}

export default PulseCommentStore;
