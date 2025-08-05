<?php

declare(strict_types=1);

namespace App\Http\Requests;

use PreludeSo\Laravel\Http\Requests\CreateVerificationRequest;

class CreateOtpRequest extends CreateVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * This example shows how to customize the parent validation rules
     * for specific use cases. You can either use parent::rules() to inherit
     * all rules or override them completely.
     */
    public function rules(): array
    {
        // Option 1: Use all parent rules
        // return parent::rules();
        
        // Option 2: Override with custom rules for specific requirements
        return [
            // Dispatch ID (optional) - from frontend SDK
            'dispatch_id' => 'nullable|string|max:255',
            
            // Metadata (optional) - tracking information
            'metadata' => 'nullable|array',
            'metadata.source' => 'nullable|string|max:100',
            'metadata.user_id' => 'nullable|string|max:255',
            
            // Options (optional) - OTP specific settings
            'options' => 'nullable|array',
            'options.channel' => 'nullable|string|in:sms,voice',
            'options.code_length' => 'nullable|integer|in:4,6,8',
            'options.expiry_minutes' => 'nullable|integer|min:1|max:10',
            
            // Signals (optional) - basic browser information
            'signals' => 'nullable|array',
            'signals.ip_address' => 'nullable|string|ip',
            'signals.user_agent' => 'nullable|string|max:1000',
            
            // Target (required) - phone number only for OTP
            'target.type' => 'required|string|in:phone_number',
            'target.value' => [
                'required',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/',
            ],
        ];
    }
}