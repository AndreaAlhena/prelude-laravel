<?php

declare(strict_types=1);

namespace PreludeSo\Laravel\Traits;

use PreludeSo\SDK\PreludeClient;

trait InteractsWithPrelude
{
    /**
     * Check a verification OTP code.
     */
    protected function checkVerification(mixed $verificationId, mixed $code): mixed
    {
        return $this->_prelude()->verification()->check($verificationId, $code);
    }

    /**
     * Create a verification for a phone number or email.
     */
    protected function createVerification(mixed $target, mixed $targetType = null, mixed $options = null): mixed
    {
        return $this->_prelude()->verification()->create($target, $targetType, $options);
    }

    /**
     * Lookup phone number information.
     */
    protected function lookupPhoneNumber(string $phoneNumber, array $features = []): mixed
    {
        return $this->_prelude()->lookup()->lookup($phoneNumber, $features);
    }

    /**
     * Resend OTP for a verification.
     */
    protected function resendVerificationOtp(string $verificationId): mixed
    {
        return $this->_prelude()->verification()->resendOtp($verificationId);
    }

    /**
     * Send a transactional message.
     */
    protected function sendTransactionalMessage(string $target, string $templateId, mixed $options = null): mixed
    {
        return $this->_prelude()->transactional()->send($target, $templateId, $options);
    }

    /**
     * Sync model data with Prelude.
     */
    protected function syncWithPrelude(array $data, string $endpoint = ''): mixed
    {
        $endpoint = $endpoint ?: $this->_getPreludeEndpoint();
        
        return $this->_sendToPrelude($endpoint, $data);
    }

    /**
     * Get the default Prelude endpoint for this model.
     * Override this method in your model to customize the endpoint.
     */
    private function _getPreludeEndpoint(): string
    {
        $className = class_basename($this);
        return '/api/' . strtolower($className);
    }

    /**
     * Get the Prelude client instance.
     */
    private function _prelude(): PreludeClient
    {
        return app(PreludeClient::class);
    }

    /**
     * Send data to Prelude endpoint.
     */
    private function _sendToPrelude(string $endpoint, array $data): mixed
    {
        // Implementation would depend on specific requirements
        // This is a placeholder for the actual implementation
        return $this->_prelude();
    }
}