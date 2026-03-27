<?php

namespace App\Http\Requests;

use App\Models\ShippingAddress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isShopper() ?? false;
    }

    public function rules(): array
    {
        return [
            'shipping_address_id' => [
                'required',
                'integer',
                Rule::exists(ShippingAddress::class, 'id')->where(
                    fn ($query) => $query->where('user_id', $this->user()?->getKey())
                ),
            ],
            'user_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
