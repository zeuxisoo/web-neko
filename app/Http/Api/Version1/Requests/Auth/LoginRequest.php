<?php

namespace App\Http\Api\Version1\Requests\Auth;

use App\Http\Api\Version1\Bases\ApiFormRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\Rule|string>
     */
    public function rules(): array {
        return [
            'account' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void {
        $this->ensureIsNotRateLimited();

        $input = $this->only('password');
        $input = array_merge($input, $this->getAccountColumnInput());
        $remember = $this->boolean('remember');

        $attempt = Auth::guard('web')->attempt($input, $remember);

        if (!$attempt) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages(['account' => trans('auth.failed')]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'account' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string {
        return Str::transliterate(Str::lower($this->input('account')).'|'.$this->ip());
    }

    /**
     * Determine and create the account input base on the request account field
     *
     * @return array<string, string>
     */
    public function getAccountColumnInput(): array {
        $account = $this->input('account');
        $columnName = filter_var($account, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $columnName => $account,
        ];
    }

    /**
     * Determine the account column name in users table base on the request account field
     *
     * @return string>
     */
    public function getAccountColumnName(): string {
        return filter_var($this->input('account'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }
}
