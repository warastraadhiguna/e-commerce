<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PublicLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
            'redirect' => ['nullable', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $phone = \App\Models\User::normalizePhone($this->string('phone')->toString());

        if (! Auth::attempt([
            'phone' => $phone,
            'password' => $this->string('password')->toString(),
            'is_active' => true,
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => 'Nomor HP, password, atau status akun tidak valid.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function safeRedirectTo(): string
    {
        $redirect = trim((string) $this->input('redirect', ''));

        if ($redirect === '' || ! str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            return route('home');
        }

        return $redirect;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    protected function throttleKey(): string
    {
        $phone = \App\Models\User::normalizePhone($this->string('phone')->toString()) ?? '';

        return Str::transliterate(Str::lower($phone).'|'.$this->ip());
    }
}
