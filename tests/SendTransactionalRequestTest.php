<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;

use PreludeSo\Laravel\Http\Requests\SendTransactionalRequest;

test('validates valid phone number recipient', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates valid email recipient', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => 'user@example.com',
        'template_id' => 'welcome_template',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation with invalid recipient format', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => 'invalid-recipient',
        'template_id' => 'welcome_template',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('to'))->toBeTrue();
});

test('fails validation with missing recipient', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'template_id' => 'welcome_template',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('to'))->toBeTrue();
});

test('fails validation with missing template_id', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('template_id'))->toBeTrue();
});

test('validates complete request with all optional fields', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'from' => 'MyApp',
            'channel' => 'sms',
            'priority' => 'high',
            'scheduled_at' => now()->addHour()->toISOString(),
            'callback_url' => 'https://example.com/callback',
            'webhook_url' => 'https://example.com/webhook',
            'variables' => [
                'name' => 'John Doe',
                'code' => '123456',
            ],
            'attachments' => [
                'https://example.com/file.pdf',
            ],
            'reply_to' => 'noreply@example.com',
            'subject' => 'Welcome to our service',
            'tags' => ['welcome', 'onboarding'],
        ],
        'metadata' => [
            'user_id' => 'user123',
            'source' => 'web',
            'campaign_id' => 'camp456',
            'reference_id' => 'ref789',
            'custom_fields' => [
                'department' => 'marketing',
                'region' => 'us-east',
            ],
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('validates request with only required fields', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => 'user@example.com',
        'template_id' => 'simple_template',
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->passes())->toBeTrue();
});

test('fails validation with invalid channel', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'channel' => 'invalid_channel',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.channel'))->toBeTrue();
});

test('fails validation with invalid priority', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'priority' => 'urgent',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.priority'))->toBeTrue();
});

test('fails validation with invalid scheduled_at in past', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'scheduled_at' => now()->subHour()->toISOString(),
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.scheduled_at'))->toBeTrue();
});

test('fails validation with invalid callback_url', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'callback_url' => 'not-a-valid-url',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.callback_url'))->toBeTrue();
});

test('fails validation with invalid reply_to email', function () {
    $request = new SendTransactionalRequest();
    $data = [
        'to' => '+1234567890',
        'template_id' => 'welcome_template',
        'options' => [
            'reply_to' => 'invalid-email',
        ],
    ];
    
    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('options.reply_to'))->toBeTrue();
});

test('validates international phone numbers', function () {
    $request = new SendTransactionalRequest();
    $phoneNumbers = [
        '+44123456789',   // UK
        '+33123456789',   // France
        '+81123456789',   // Japan
        '+861234567890',  // China
        '+5511234567890', // Brazil
    ];
    
    foreach ($phoneNumbers as $phoneNumber) {
        $data = [
            'to' => $phoneNumber,
            'template_id' => 'test_template',
        ];
        
        $validator = Validator::make($data, $request->rules());
        expect($validator->passes())->toBeTrue("Failed for phone number: {$phoneNumber}");
    }
});

test('fails validation with invalid phone number formats', function () {
    $request = new SendTransactionalRequest();
    $invalidPhones = [
        '123',           // Too short
        '0123456789',    // Starts with 0
        '+0123456789',   // Starts with +0
        'abc123456789',  // Contains letters
        '+1-234-567-890', // Contains dashes
    ];
    
    foreach ($invalidPhones as $invalidPhone) {
        $data = [
            'to' => $invalidPhone,
            'template_id' => 'test_template',
        ];
        
        $validator = Validator::make($data, $request->rules());
        expect($validator->fails())->toBeTrue("Should fail for phone number: {$invalidPhone}");
    }
});