<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Feedbacks (required) - array of feedback objects
            'feedbacks' => 'required|array|min:1',
            'feedbacks.*.dispatch_id' => 'nullable|string|max:255',
            'feedbacks.*.metadata' => 'nullable|array',
            'feedbacks.*.metadata.campaign_id' => 'nullable|string|max:255',
            'feedbacks.*.metadata.custom_fields' => 'nullable|array',
            'feedbacks.*.metadata.custom_fields.*' => 'string|max:500',
            'feedbacks.*.metadata.reference_id' => 'nullable|string|max:255',
            'feedbacks.*.metadata.source' => 'nullable|string|max:100',
            'feedbacks.*.metadata.user_id' => 'nullable|string|max:255',
            'feedbacks.*.signals' => 'nullable|array',
            'feedbacks.*.signals.browser_plugins' => 'nullable|array',
            'feedbacks.*.signals.browser_plugins.*' => 'string|max:100',
            'feedbacks.*.signals.device_fingerprint' => 'nullable|string|max:500',
            'feedbacks.*.signals.ip_address' => 'nullable|string|ip',
            'feedbacks.*.signals.language' => 'nullable|string|max:10',
            'feedbacks.*.signals.screen_resolution' => 'nullable|string|max:20',
            'feedbacks.*.signals.session_id' => 'nullable|string|max:255',
            'feedbacks.*.signals.timestamp' => 'nullable|integer|min:0',
            'feedbacks.*.signals.timezone' => 'nullable|string|max:50',
            'feedbacks.*.signals.user_agent' => 'nullable|string|max:1000',
            'feedbacks.*.target' => 'required|array',
            'feedbacks.*.target.type' => 'required|string|in:phone_number,email_address',
            'feedbacks.*.target.value' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $type = request()->input("feedbacks.{$index}.target.type");
                    
                    if ($type === 'phone_number') {
                        if (!preg_match('/^\+[1-9]\d{6,14}$/', $value)) {
                            $fail('The phone number must be in international format (+1234567890).');
                        }
                    } elseif ($type === 'email_address') {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('The email address must be a valid email.');
                        }
                    }
                },
            ],
            'feedbacks.*.type' => 'required|string|max:100',
        ];
    }
}