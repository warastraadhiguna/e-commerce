<?php

namespace App\Http\Requests;

use App\Enums\PartnerRegistrationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePartnerRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['bail', 'required', 'string', 'min:3', 'max:120'],
            'whatsapp' => ['bail', 'required', 'string', 'regex:/^[0-9+\-\s()]{8,25}$/'],
            'message' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.max' => 'Permintaan tidak valid.',
            'whatsapp.regex' => 'Format No. WhatsApp tidak valid.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function sanitized(): array
    {
        $fullName = Str::of(strip_tags((string) $this->input('full_name')))
            ->squish()
            ->toString();

        $whatsapp = Str::of((string) $this->input('whatsapp'))
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->toString();

        $message = Str::of(strip_tags((string) $this->input('message')))
            ->replace("\r\n", "\n")
            ->replace("\r", "\n")
            ->trim()
            ->toString();

        return [
            'full_name' => $fullName,
            'whatsapp' => $whatsapp,
            'message' => blank($message) ? null : $message,
            'status' => PartnerRegistrationStatus::Pending,
            'ip_address' => $this->ip(),
            'user_agent' => Str::limit((string) $this->userAgent(), 500, ''),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('contact') . '#partner-registration';
    }
}
