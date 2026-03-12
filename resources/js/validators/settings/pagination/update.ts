import Validator from '@/validators/validator';

class SettingsPaginationUpdate extends Validator {
    rules(): Record<string, string[]> {
        return {
            per_page_attachment: ['required', 'integer', 'gte:1'],
            per_page_bookmark: ['required', 'integer', 'gte:1'],
            per_page_comment: ['required', 'integer', 'gte:1'],
            per_page_link: ['required', 'integer', 'gte:1'],
            per_page_memo: ['required', 'integer', 'gte:1'],
            per_page_drift: ['required', 'integer', 'gte:1'],
        };
    }

    messages(): Record<string, string> {
        return {
            'per_page_attachment.required': 'Please enter per page attachment',
            'per_page_attachment.integer': 'Per page attachment must be integer',
            'per_page_attachment.gte': 'Per page attachment must greater than or equals 1',
            'per_page_bookmark.required': 'Please enter per page bookmark',
            'per_page_bookmark.integer': 'Per page bookmark must be integer',
            'per_page_bookmark.gte': 'Per page bookmark must greater than or equals 1',
            'per_page_comment.required': 'Please enter per page comment',
            'per_page_comment.integer': 'Per page comment must be integer',
            'per_page_comment.gte': 'Per page comment must greater than or equals 1',
            'per_page_link.required': 'Please enter per page link',
            'per_page_link.integer': 'Per page link must be integer',
            'per_page_link.gte': 'Per page link must greater than or equals 1',
            'per_page_memo.required': 'Please enter per page memo',
            'per_page_memo.integer': 'Per page memo must be integer',
            'per_page_memo.gte': 'Per page memo must greater than or equals 1',
            'per_page_drift.required': 'Please enter per page drift',
            'per_page_drift.integer': 'Per page drift must be integer',
            'per_page_drift.gte': 'Per page drift must greater than or equals 1',
        };
    }
}

export default SettingsPaginationUpdate;
