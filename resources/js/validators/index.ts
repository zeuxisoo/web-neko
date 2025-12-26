import AuthLoginValidator from './auth/login';
import { ValidateError } from './error';
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
    'auth.login': AuthLoginValidator,
};

const validator = {
    form(name: string): Validator {
        return new validators[name]();
    },
};

export default validator;
export {
    ValidateError
};
