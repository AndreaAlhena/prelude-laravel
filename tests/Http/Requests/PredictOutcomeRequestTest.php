<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Tests\Http\Requests;

use PHPUnit\Framework\TestCase;
use PreludeSo\Laravel\Http\Requests\PredictOutcomeRequest;

class PredictOutcomeRequestTest extends TestCase
{
    private PredictOutcomeRequest $_request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->_request = new PredictOutcomeRequest();
    }

    public function testRulesReturnsCorrectValidationRules(): void
    {
        $rules = $this->_request->rules();

        // Test dispatch_id rules
        $this->assertArrayHasKey('dispatch_id', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['dispatch_id']);

        // Test metadata rules
        $this->assertArrayHasKey('metadata', $rules);
        $this->assertEquals('nullable|array', $rules['metadata']);
        $this->assertArrayHasKey('metadata.campaign_id', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['metadata.campaign_id']);
        $this->assertArrayHasKey('metadata.custom_fields', $rules);
        $this->assertEquals('nullable|array', $rules['metadata.custom_fields']);
        $this->assertArrayHasKey('metadata.custom_fields.*', $rules);
        $this->assertEquals('string|max:500', $rules['metadata.custom_fields.*']);
        $this->assertArrayHasKey('metadata.reference_id', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['metadata.reference_id']);
        $this->assertArrayHasKey('metadata.source', $rules);
        $this->assertEquals('nullable|string|max:100', $rules['metadata.source']);
        $this->assertArrayHasKey('metadata.user_id', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['metadata.user_id']);

        // Test signals rules
        $this->assertArrayHasKey('signals', $rules);
        $this->assertEquals('required|array', $rules['signals']);
        $this->assertArrayHasKey('signals.browser_plugins', $rules);
        $this->assertEquals('nullable|array', $rules['signals.browser_plugins']);
        $this->assertArrayHasKey('signals.browser_plugins.*', $rules);
        $this->assertEquals('string|max:100', $rules['signals.browser_plugins.*']);
        $this->assertArrayHasKey('signals.device_fingerprint', $rules);
        $this->assertEquals('nullable|string|max:500', $rules['signals.device_fingerprint']);
        $this->assertArrayHasKey('signals.ip_address', $rules);
        $this->assertEquals('nullable|string|ip', $rules['signals.ip_address']);
        $this->assertArrayHasKey('signals.language', $rules);
        $this->assertEquals('nullable|string|max:10', $rules['signals.language']);
        $this->assertArrayHasKey('signals.screen_resolution', $rules);
        $this->assertEquals('nullable|string|max:20', $rules['signals.screen_resolution']);
        $this->assertArrayHasKey('signals.session_id', $rules);
        $this->assertEquals('nullable|string|max:255', $rules['signals.session_id']);
        $this->assertArrayHasKey('signals.timestamp', $rules);
        $this->assertEquals('nullable|integer|min:0', $rules['signals.timestamp']);
        $this->assertArrayHasKey('signals.timezone', $rules);
        $this->assertEquals('nullable|string|max:50', $rules['signals.timezone']);
        $this->assertArrayHasKey('signals.user_agent', $rules);
        $this->assertEquals('nullable|string|max:1000', $rules['signals.user_agent']);

        // Test target rules
        $this->assertArrayHasKey('target.type', $rules);
        $this->assertEquals('required|string|in:phone_number,email_address', $rules['target.type']);
        $this->assertArrayHasKey('target.value', $rules);
        $this->assertIsArray($rules['target.value']);
        $this->assertContains('required', $rules['target.value']);
        $this->assertContains('string', $rules['target.value']);
    }

    public function testRulesAreAlphabeticallySorted(): void
    {
        $rules = $this->_request->rules();
        $keys = array_keys($rules);
        $sortedKeys = $keys;
        sort($sortedKeys);

        $this->assertEquals($sortedKeys, $keys, 'Rules array keys should be alphabetically sorted');
    }

    public function testTargetValidationCustomRule(): void
    {
        $rules = $this->_request->rules();
        $targetValueRule = $rules['target.value'];
        
        $this->assertIsArray($targetValueRule);
        $this->assertCount(3, $targetValueRule);
        $this->assertEquals('required', $targetValueRule[0]);
        $this->assertEquals('string', $targetValueRule[1]);
        $this->assertIsCallable($targetValueRule[2]);
    }
}