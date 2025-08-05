<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use PreludeSo\Laravel\Http\Requests\SendFeedbackRequest;

test('form request extends laravel form request', function () {
    $request = new SendFeedbackRequest();
    expect($request)->toBeInstanceOf(\Illuminate\Foundation\Http\FormRequest::class);
});

test('validates valid feedback with phone number target', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates valid feedback with email target', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'email_address',
                    'value' => 'test@example.com',
                ],
                'type' => 'declined',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates feedback with all optional fields', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'dispatch_id' => 'dispatch-123',
                'metadata' => [
                    'campaign_id' => 'campaign-456',
                    'custom_fields' => [
                        'field1' => 'value1',
                        'field2' => 'value2',
                    ],
                    'reference_id' => 'ref-789',
                    'source' => 'web',
                    'user_id' => 'user-123',
                ],
                'signals' => [
                    'browser_plugins' => ['plugin1', 'plugin2'],
                    'device_fingerprint' => 'fingerprint-abc',
                    'ip_address' => '192.168.1.1',
                    'language' => 'en-US',
                    'screen_resolution' => '1920x1080',
                    'session_id' => 'session-xyz',
                    'timestamp' => 1234567890,
                    'timezone' => 'America/New_York',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ],
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
                'type' => 'fraud',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation when feedbacks is missing', function () {
    $request = new SendFeedbackRequest();
    $request->merge([]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks'))->toBeTrue();
});

test('fails validation when feedbacks is empty array', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks'))->toBeTrue();
});

test('fails validation when target is missing', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target'))->toBeTrue();
});

test('fails validation when target type is missing', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.type'))->toBeTrue();
});

test('fails validation when target value is missing', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation when type is missing', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.type'))->toBeTrue();
});

test('fails validation with invalid target type', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'invalid_type',
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.type'))->toBeTrue();
});

test('fails validation with invalid email format', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'email_address',
                    'value' => 'invalid-email',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation with invalid phone number format', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => 'invalid-phone',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation with phone number without plus sign', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation with phone number starting with zero', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+0123456789',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation with phone number too short', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+123456',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('validates phone number with minimum length', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates phone number with maximum length', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+123456789012345',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation with phone number too long', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890123456',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.target.value'))->toBeTrue();
});

test('fails validation with invalid ip address in signals', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'signals' => [
                    'ip_address' => 'invalid-ip',
                ],
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.signals.ip_address'))->toBeTrue();
});

test('fails validation with negative timestamp in signals', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'signals' => [
                    'timestamp' => -1,
                ],
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('feedbacks.0.signals.timestamp'))->toBeTrue();
});

test('validates multiple feedbacks', function () {
    $request = new SendFeedbackRequest();
    $request->merge([
        'feedbacks' => [
            [
                'target' => [
                    'type' => 'phone_number',
                    'value' => '+1234567890',
                ],
                'type' => 'approved',
            ],
            [
                'target' => [
                    'type' => 'email_address',
                    'value' => 'test@example.com',
                ],
                'type' => 'declined',
            ],
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});