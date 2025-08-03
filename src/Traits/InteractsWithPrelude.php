<?php

namespace PreludeSo\Laravel\Traits;

use PreludeSo\Sdk\PreludeClient;

trait InteractsWithPrelude
{
    /**
     * Get the Prelude client instance.
     */
    protected function prelude(): PreludeClient
    {
        return app(PreludeClient::class);
    }

    /**
     * Create a verification for a phone number or email.
     */
    protected function createVerification(string $target, $targetType = null, array $options = []): mixed
    {
        return $this->prelude()->verification()->create($target, $targetType, $options);
    }

    /**
     * Check a verification OTP code.
     */
    protected function checkVerification(string $verificationId, string $code): mixed
    {
        return $this->prelude()->verification()->check($verificationId, $code);
    }

    /**
     * Resend OTP for a verification.
     */
    protected function resendVerificationOtp(string $verificationId): mixed
    {
        return $this->prelude()->verification()->resendOtp($verificationId);
    }

    /**
     * Lookup phone number information.
     */
    protected function lookupPhoneNumber(string $phoneNumber, array $features = []): mixed
    {
        return $this->prelude()->lookup()->lookup($phoneNumber, $features);
    }

    /**
     * Send a transactional message.
     */
    protected function sendTransactionalMessage(string $target, string $templateId, $options = null): mixed
    {
        return $this->prelude()->transactional()->send($target, $templateId, $options);
    }

    /**
     * Sync model data with Prelude.
     */
    protected function syncWithPrelude(array $data, string $endpoint = null): mixed
    {
        $endpoint = $endpoint ?? $this->getPreludeEndpoint();
        
        return $this->sendToPrelude($endpoint, $data);
    }

    /**
     * Get the default Prelude endpoint for this model.
     * Override this method in your model to customize the endpoint.
     */
    protected function getPreludeEndpoint(): string
    {
        $className = class_basename($this);
        return '/api/' . strtolower($className);
    }
}