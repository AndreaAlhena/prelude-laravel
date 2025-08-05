<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTransactionalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Metadata (optional) - custom tracking data
            'metadata' => 'nullable|array',
            'metadata.campaign_id' => 'nullable|string|max:255',
            'metadata.custom_fields' => 'nullable|array',
            'metadata.custom_fields.*' => 'string|max:500',
            'metadata.reference_id' => 'nullable|string|max:255',
            'metadata.source' => 'nullable|string|max:100',
            'metadata.user_id' => 'nullable|string|max:255',

            // Options (optional) - message configuration
            'options' => 'nullable|array',
            'options.attachments' => 'nullable|array',
            'options.attachments.*' => 'string|url|max:500',
            'options.callback_url' => 'nullable|string|url|max:500',
            'options.channel' => 'nullable|string|in:sms,email,whatsapp,voice',
            'options.from' => 'nullable|string|max:255',
            'options.priority' => 'nullable|string|in:low,normal,high',
            'options.reply_to' => 'nullable|string|email|max:255',
            'options.scheduled_at' => 'nullable|date|after:now',
            'options.subject' => 'nullable|string|max:255',
            'options.tags' => 'nullable|array',
            'options.tags.*' => 'string|max:100',
            'options.variables' => 'nullable|array',
            'options.variables.*' => 'string|max:1000',
            'options.webhook_url' => 'nullable|string|url|max:500',

            // Template ID (required) - message template identifier
            'template_id' => 'required|string|max:255',

            // To (required) - recipient phone number or email
            'to' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Check if it's a valid phone number (international format with at least 7 digits)
                    $isValidPhone = preg_match('/^\+?[1-9]\d{6,14}$/', $value);
                    $isValidEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    
                    if (!$isValidPhone && !$isValidEmail) {
                        $fail('The recipient must be a valid phone number or email address.');
                    }
                },
            ],
        ];
    }
}