<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use PreludeSo\Laravel\Http\Requests\CreateVerificationRequest;

test('form request extends laravel form request', function () {
    $request = new CreateVerificationRequest();
    expect($request)->toBeInstanceOf(FormRequest::class);
});

test('validates valid phone number target', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates valid email target', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'email_address',
            'value' => 'test@example.com',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation when target is missing', function () {
    $request = new CreateVerificationRequest();
    $data = [];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid target type', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'invalid_type',
            'value' => 'test@example.com',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
});

test('fails validation with invalid email format', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'email_address',
            'value' => 'invalid-email-format',
        ],
    ];
    
    // Manually set the request data for the custom validation to work
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid phone number format', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'phone_number',
            'value' => 'invalid-phone',
        ],
    ];
    
    // Manually set the request data for the custom validation to work
    $request->merge($data);
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('validates complete request with all optional fields', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'signals' => [
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'device_fingerprint' => 'abc123',
        ],
        'options' => [
            'template' => 'custom_template',
            'expiry_minutes' => 10,
            'code_length' => 6,
            'locale' => 'en',
        ],
        'metadata' => [
            'user_id' => 'user123',
            'source' => 'web',
            'campaign_id' => 'camp456',
        ],
        'dispatch_id' => 'dispatch789',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates request with only required fields', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'email_address',
            'value' => 'user@domain.com',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation with invalid ip address in signals', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'signals' => [
            'ip_address' => 'invalid-ip',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('signals.ip_address'))->toBeTrue();
});

test('fails validation with invalid expiry_minutes in options', function () {
    $request = new CreateVerificationRequest();
    $data = [
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'options' => [
            'expiry_minutes' => 'not-a-number',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.expiry_minutes'))->toBeTrue();
});