<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use PreludeSo\Laravel\Http\Requests\LookupRequest;

test('passes validation with valid phone number only', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
})->group('validation');

test('passes validation with valid phone number and type array', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => ['cnam'],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
})->group('validation');

test('passes validation with all valid lookup types', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => ['cnam'],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
})->group('validation');

test('passes validation with minimum length phone number', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567', // 7 digits after +
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
})->group('validation');

test('passes validation with maximum length phone number', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+123456789012345', // 15 digits after +
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
})->group('validation');

// Failure tests
test('fails validation when phone number is missing', function (): void {
    $request = new LookupRequest();
    $data = [];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number too short', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+123456', // 6 digits after +
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number too long', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890123456', // 16 digits after +
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number missing plus sign', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '1234567890',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number starting with zero', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+0234567890',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number containing letters', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+123abc7890',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with phone number containing special characters', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+123-456-7890',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('phone_number'))->toBeTrue();
})->group('validation');

test('fails validation with invalid lookup type', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => ['invalid_type'],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('type.0'))->toBeTrue();
})->group('validation');

test('fails validation with mixed valid and invalid lookup types', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => ['cnam', 'invalid_type', 'fraud'],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('type.1'))->toBeTrue();
})->group('validation');

test('fails validation when type is not an array', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => 'cnam',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('type'))->toBeTrue();
})->group('validation');

test('fails validation when type contains non-string values', function (): void {
    $request = new LookupRequest();
    $data = [
        'phone_number' => '+1234567890',
        'type' => ['cnam', 123, 'fraud'],
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('type.1'))->toBeTrue();
})->group('validation');