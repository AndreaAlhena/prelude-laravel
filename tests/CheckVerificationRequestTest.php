<?php

declare(strict_types=1);

use PreludeSo\Laravel\Http\Requests\CheckVerificationRequest;

test('form request extends laravel form request', function () {
    $request = new CheckVerificationRequest();
    expect($request)->toBeInstanceOf(\Illuminate\Foundation\Http\FormRequest::class);
});

test('validates valid phone number target with code', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'code' => '123456',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates valid email target with code', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'email_address',
            'value' => 'test@example.com',
        ],
        'code' => '1234',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation when target is missing', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'code' => '123456',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation when code is missing', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('code'))->toBeTrue();
});

test('fails validation with invalid target type', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'invalid_type',
            'value' => '+1234567890',
        ],
        'code' => '123456',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.type'))->toBeTrue();
});

test('fails validation with invalid email format', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'email_address',
            'value' => 'invalid-email',
        ],
        'code' => '123456',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with invalid phone number format', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => 'invalid-phone',
        ],
        'code' => '123456',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('target.value'))->toBeTrue();
});

test('fails validation with code too short', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'code' => '123',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('code'))->toBeTrue();
});

test('fails validation with code too long', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'code' => '12345678901',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('code'))->toBeTrue();
});

test('validates with minimum code length', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'code' => '1234',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates with maximum code length', function () {
    $request = new CheckVerificationRequest();
    $request->merge([
        'target' => [
            'type' => 'phone_number',
            'value' => '+1234567890',
        ],
        'code' => '1234567890',
    ]);

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $request->rules());
    expect($validator->passes())->toBeTrue();
});