<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use PreludeSo\Laravel\Http\Requests\PredictOutcomeRequest;

test('form request extends laravel form request', function () {
    $request = new PredictOutcomeRequest();
    expect($request)->toBeInstanceOf(\Illuminate\Foundation\Http\FormRequest::class);
});

test('validates valid request with phone number target', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates valid request with email target', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ],
        'target' => [
            'type' => 'email_address',
            'value' => 'test@example.com',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates request with all optional fields', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
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
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation when signals is missing', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('signals'))->toBeTrue();
});

test('fails validation when target is missing', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation when target type is missing', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
});

test('fails validation when target value is missing', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid target type', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'invalid_type',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
});

test('fails validation with invalid email format', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'email_address',
            'value' => 'invalid-email',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid phone number format', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => 'invalid-phone',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('validates phone number with optional plus sign', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates phone number with plus sign', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation with phone number starting with zero', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '0123456789',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with phone number too short', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '1',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with phone number too long', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '192.168.1.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '123456789012345678',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid ip address in signals', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => 'invalid-ip',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('signals.ip_address'))->toBeTrue();
});

test('fails validation with negative timestamp in signals', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'timestamp' => -1,
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('signals.timestamp'))->toBeTrue();
});

test('validates with valid ip addresses', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'ip_address' => '127.0.0.1',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();

    $request->merge([
        'signals' => [
            'ip_address' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates with zero timestamp in signals', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'timestamp' => 0,
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates with large positive timestamp in signals', function () {
    $request = new PredictOutcomeRequest();
    $request->merge([
        'signals' => [
            'timestamp' => 9999999999,
        ],
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});