<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Tests\Http\Requests;

use Closure;
use PHPUnit\Framework\TestCase;
use PreludeSo\Laravel\Http\Requests\SendFeedbackRequest;

class SendFeedbackRequestTest extends TestCase
{
    private SendFeedbackRequest $_request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_request = new SendFeedbackRequest();
    }

    public function testRulesReturnsCorrectValidationRules(): void
    {
        $rules = $this->_request->rules();
        
        // Check that all expected rules exist and are in alphabetical order
        $expectedRules = [
            'feedbacks',
            'feedbacks.*.dispatch_id',
            'feedbacks.*.metadata',
            'feedbacks.*.metadata.campaign_id',
            'feedbacks.*.metadata.custom_fields',
            'feedbacks.*.metadata.custom_fields.*',
            'feedbacks.*.metadata.reference_id',
            'feedbacks.*.metadata.source',
            'feedbacks.*.metadata.user_id',
            'feedbacks.*.signals',
            'feedbacks.*.signals.browser_plugins',
            'feedbacks.*.signals.browser_plugins.*',
            'feedbacks.*.signals.device_fingerprint',
            'feedbacks.*.signals.ip_address',
            'feedbacks.*.signals.language',
            'feedbacks.*.signals.screen_resolution',
            'feedbacks.*.signals.session_id',
            'feedbacks.*.signals.timestamp',
            'feedbacks.*.signals.timezone',
            'feedbacks.*.signals.user_agent',
            'feedbacks.*.target',
            'feedbacks.*.target.type',
            'feedbacks.*.target.value',
            'feedbacks.*.type',
        ];
        
        $actualRules = array_keys($rules);
        
        $this->assertEquals($expectedRules, $actualRules);
        $this->assertStringContainsString('required', $rules['feedbacks']);
        $this->assertStringContainsString('array', $rules['feedbacks']);
        $this->assertStringContainsString('min:1', $rules['feedbacks']);
        $this->assertStringContainsString('required', $rules['feedbacks.*.target.type']);
        $this->assertStringContainsString('in:phone_number,email_address', $rules['feedbacks.*.target.type']);
        $this->assertStringContainsString('required', $rules['feedbacks.*.type']);
        $this->assertStringContainsString('max:100', $rules['feedbacks.*.type']);
        $this->assertStringContainsString('nullable', $rules['feedbacks.*.dispatch_id']);
        $this->assertStringContainsString('nullable', $rules['feedbacks.*.metadata']);
        $this->assertStringContainsString('nullable', $rules['feedbacks.*.signals']);
    }

    public function testRulesAreAlphabeticallySorted(): void
    {
        $rules = $this->_request->rules();
        $keys = array_keys($rules);
        $sortedKeys = $keys;
        sort($sortedKeys);

        $this->assertEquals($sortedKeys, $keys, 'Rules array keys should be alphabetically sorted');
    }

    public function testTargetValueCustomValidationRule(): void
    {
        $rules = $this->_request->rules();
        
        // Check that target.value has custom validation
        $this->assertArrayHasKey('feedbacks.*.target.value', $rules);
        $this->assertIsArray($rules['feedbacks.*.target.value']);
        $this->assertStringContainsString('required', $rules['feedbacks.*.target.value'][0]);
        $this->assertStringContainsString('string', $rules['feedbacks.*.target.value'][1]);
        
        // Check that the third element is a closure (custom validation)
        $this->assertInstanceOf(Closure::class, $rules['feedbacks.*.target.value'][2]);
    }
}