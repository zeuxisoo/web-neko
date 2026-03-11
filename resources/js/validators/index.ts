import { AccountProfileUpdate, AccountSecurityUpdate } from './account';
import AuthLogin from './auth/login';
import { DriftStore } from './drift';
import DriftUpdate from './drift/update';
import { ValidateError } from './error';
import { PulseCommentStore, PulseMemoStore, PulseMemoUpdate } from './pulse';
import { SettingsAttachmentUpdate, SettingsPaginationUpdate } from './settings';
import Validator from './validator';

/*
 * // Simple usage
 * const account = validator.form('login')
 *   .validate({
 *       email   : viewState.email,
 *       password: viewState.password,
 *   });
 *
 * // Complex usage
 * const account = validator.form('login')
 *   .addRuler('custom', item => { console.log(item); return false; })
 *   .addRule('email', ['custom'])
 *   .addMessage('email.custom', 'Custom email error')
 *   .validate({
 *       email   : viewState.email,
 *       password: viewState.password,
 *   });
 */
const validators: Record<string, typeof Validator> = {
    'auth.login': AuthLogin,
    'account.profile.update': AccountProfileUpdate,
    'account.security.update': AccountSecurityUpdate,
    'pulse.memo.store': PulseMemoStore,
    'pulse.memo.update': PulseMemoUpdate,
    'pulse.comment.store': PulseCommentStore,
    'settings.attachment.update': SettingsAttachmentUpdate,
    'settings.pagination.update': SettingsPaginationUpdate,
    'drift.store': DriftStore,
    'drift.update': DriftUpdate,
};

const validator = {
    form(name: keyof typeof validators): Validator {
        return new validators[name]();
    },
};

export default validator;
export { ValidateError };
