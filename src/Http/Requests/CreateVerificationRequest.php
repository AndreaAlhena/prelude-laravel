<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Dispatch ID (optional) - from frontend SDK
            'dispatch_id' => 'nullable|string|max:255',

            // Metadata (optional) - custom data
            'metadata' => 'nullable|array',
            'metadata.campaign_id' => 'nullable|string|max:255',
            'metadata.custom_fields' => 'nullable|array',
            'metadata.custom_fields.*' => 'string|max:500',
            'metadata.reference_id' => 'nullable|string|max:255',
            'metadata.source' => 'nullable|string|max:100',
            'metadata.user_id' => 'nullable|string|max:255',

            // Options (optional) - verification configuration
            'options' => 'nullable|array',
            'options.brand_name' => 'nullable|string|max:100',
            'options.callback_url' => 'nullable|string|url|max:500',
            'options.channel' => 'nullable|string|in:sms,voice,email,whatsapp',
            'options.code_length' => 'nullable|integer|min:4|max:10',
            'options.expiry_minutes' => 'nullable|integer|min:1|max:60',
            'options.locale' => 'nullable|string|max:10',
            'options.rate_limit' => 'nullable|integer|min:1|max:10',
            'options.template' => 'nullable|string|max:255',
            'options.webhook_url' => 'nullable|string|url|max:500',

            // Signals (optional) - browser/device information
            'signals' => 'nullable|array',
            'signals.browser_plugins' => 'nullable|array',
            'signals.browser_plugins.*' => 'string|max:100',
            'signals.device_fingerprint' => 'nullable|string|max:500',
            'signals.ip_address' => 'nullable|string|ip',
            'signals.language' => 'nullable|string|max:10',
            'signals.screen_resolution' => 'nullable|string|max:20',
            'signals.session_id' => 'nullable|string|max:255',
            'signals.timestamp' => 'nullable|integer|min:0',
            'signals.timezone' => 'nullable|string|max:50',
            'signals.user_agent' => 'nullable|string|max:1000',

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