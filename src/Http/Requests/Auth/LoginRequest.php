<?php

namespace Armincms\Api\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends AuthRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            $this->credentialKey() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attemp to login.
     *
     * @return boolean
     */
    public function attempt()
    {
        $provider = Auth::guard('web')->getProvider();

        if (! is_null($user = $provider->retrieveByCredentials($this->credentials()))) {
            $provider->validateCredentials($user, $this->credentials());
        }

        return $user;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (is_null($user = $this->attempt())) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $this->credentialKey() => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return $user;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->credentialKey() => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input($this->credentialKey())).'|'.$this->ip();
    }

    /**
     * Get the login credentials from the request.
     *
     * @return array
     */
    public function credentials()
    {
        return [
            $this->credentialKey() => $this->input($this->credentialKey()),
            'password' => $this->input('password'),
        ];
    }
}
