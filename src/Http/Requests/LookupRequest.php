<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use PreludeSo\SDK\Enums\LookupType;

class LookupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Phone number (required) - must be in E.164 format
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+[1-9]\d{6,14}$/',
            ],

            // Type (optional) - array of lookup features
            'type' => 'nullable|array',
            'type.*' => ['string', new Enum(LookupType::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.regex' => 'The phone number must be in E.164 format (e.g., +1234567890).',
            'type.array' => 'The type field must be an array.',
            'type.*.in' => 'Each type must be a valid lookup type (cnam, network_info, fraud).',
        ];
    }
}