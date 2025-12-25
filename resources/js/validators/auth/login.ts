import Validator from '../validator';

class Login extends Validator {

    rules(): Record<string, string[]> {
        return {
            account : ['required'],
            password: ['required', 'min:8'],
        };
    }

    messages(): Record<string, string> {
        return {
            'account.required' : 'Please enter account',
            'password.required': 'Please enter password',
            'password.min'     : 'Password letters must be more than %(args[0])s',
        };
    }

}

export default Login;
