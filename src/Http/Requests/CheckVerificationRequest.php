<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckVerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Code (required) - verification code to check
            'code' => 'required|string|min:4|max:10',

            // Target (required) - phone number or email
            'target.type' => 'required|string|in:phone_number,email_address',
            'target.value' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $targetType = $this->input('target.type');
                    if ($targetType === 'phone_number' && !preg_match('/^\+?[1-9]\d{1,14}$/', $value)) {
                        $fail('The phone number format is invalid.');
                    } elseif ($targetType === 'email_address' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('The email address format is invalid.');
                    }
                },
            ],
        ];
    }
}