<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PreludeSo\Laravel\Facades\Prelude;
use PreludeSo\Laravel\Http\Requests\CheckVerificationRequest;
use PreludeSo\Laravel\Http\Requests\CreateVerificationRequest;
use PreludeSo\Laravel\Http\Requests\LookupRequest;
use PreludeSo\Laravel\Http\Requests\PredictOutcomeRequest;
use PreludeSo\Laravel\Http\Requests\SendFeedbackRequest;
use PreludeSo\Laravel\Http\Requests\SendTransactionalRequest;
use PreludeSo\Laravel\Traits\InteractsWithPrelude;
use PreludeSo\Sdk\PreludeClient;

/**
 * Example controller demonstrating Prelude package usage.
 * 
 * This file shows complete examples of:
 * - Phone verification creation and checking
 * - Phone number lookup
 * - Transactional messaging
 * - Error handling patterns
 * - Both Facade and dependency injection usage
 * 
 * For testing examples, see the tests/ directory which uses Pest testing framework.
 */
class UserController extends Controller
{
    use InteractsWithPrelude;
    /**
     * Example: Create verification with comprehensive validation using CreateVerificationRequest.
     */
    public function createVerificationWithValidation(CreateVerificationRequest $request): JsonResponse
    {
        try {
            // All validation is handled by CreateVerificationRequest
            // Access validated data safely
            $target = $request->validated('target');
            $signals = $request->validated('signals');
            $options = $request->validated('options');
            $metadata = $request->validated('metadata');
            $dispatchId = $request->validated('dispatch_id');
            
            // Create verification with all parameters
            // Note: This example shows the structure - actual SDK integration may vary
            $verification = Prelude::verification()->create(
                $target['value'],
                $target['type'],
                [
                    'signals' => $signals,
                    'options' => $options,
                    'metadata' => $metadata,
                    'dispatch_id' => $dispatchId,
                ]
            );
            
            return response()->json([
                'verification_id' => $verification->getId(),
                'status' => $verification->getStatus(),
                'expires_at' => $verification->getExpiresAt(),
                'target_type' => $target['type'],
                'has_signals' => !empty($signals),
                'has_metadata' => !empty($metadata),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Create a phone verification using the Facade.
     */
    public function createVerificationWithFacade(Request $request): JsonResponse
    {
        try {
            $phoneNumber = $request->input('phone_number');
            $verification = Prelude::verification()->create($phoneNumber);
            
            return response()->json([
                'verification_id' => $verification->getId(),
                'status' => $verification->getStatus(),
                'expires_at' => $verification->getExpiresAt()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Check verification with comprehensive validation using CheckVerificationRequest.
     */
    public function checkVerificationWithValidation(CheckVerificationRequest $request): JsonResponse
    {
        try {
            // All validation is handled by CheckVerificationRequest
            // Access validated data safely
            $target = $request->validated('target');
            $code = $request->validated('code');
            
            // Create target object for SDK
            $targetObject = new \PreludeSo\Sdk\Target(
                $target['value'],
                $target['type']
            );
            
            // Check verification using the SDK
            $result = Prelude::verification()->check($targetObject, $code);
            
            return response()->json([
                'success' => $result->isSuccess(),
                'status' => $result->getStatus()->value,
                'target_type' => $target['type']
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Check verification OTP using dependency injection.
     */
    public function checkVerificationWithDI(Request $request, PreludeClient $prelude): JsonResponse
    {
        try {
            $verificationId = $request->input('verification_id');
            $code = $request->input('code');
            
            $result = $prelude->verification()->check($verificationId, $code);
            
            return response()->json([
                'success' => $result->isSuccess(),
                'status' => $result->getStatus()->value
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Lookup phone number using the Facade.
     */
    public function lookupPhone(Request $request): JsonResponse
    {
        try {
            $phoneNumber = $request->input('phone_number');
            $result = Prelude::lookup()->phoneNumber($phoneNumber);
            
            return response()->json([
                'phone_number' => $result->getPhoneNumber(),
                'carrier' => $result->getCarrier(),
                'country' => $result->getCountry(),
                'is_valid' => $result->isValid()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Send transactional message.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $phoneNumber = $request->input('phone_number');
            $message = $request->input('message');
            
            $result = Prelude::transactional()->send($phoneNumber, $message);
            
            return response()->json([
                'message_id' => $result->getId(),
                'status' => $result->getStatus(),
                'sent_at' => $result->getSentAt()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Send transactional message with comprehensive validation using SendTransactionalRequest.
     */
    public function sendTransactionalWithValidation(SendTransactionalRequest $request): JsonResponse
    {
        try {
            // All validation is handled by SendTransactionalRequest
            // Access validated data safely
            $to = $request->validated('to');
            $templateId = $request->validated('template_id');
            $options = $request->validated('options');
            $metadata = $request->validated('metadata');
            
            // Create options object for SDK if provided
            $optionsObject = null;
            if ($options) {
                // Note: This example shows the structure - actual SDK Options class may vary
                $optionsObject = new \PreludeSo\Sdk\Options($options);
            }
            
            // Send transactional message using the SDK
            $result = Prelude::transactional()->send($to, $templateId, $optionsObject);
            
            return response()->json([
                'message_id' => $result->getId(),
                'status' => $result->getStatus(),
                'sent_at' => $result->getSentAt(),
                'recipient' => $to,
                'template_id' => $templateId,
                'has_options' => !empty($options),
                'has_metadata' => !empty($metadata),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Predict outcome with comprehensive validation using PredictOutcomeRequest.
     */
    public function predictOutcomeWithValidation(PredictOutcomeRequest $request): JsonResponse
    {
        try {
            // All validation is handled by PredictOutcomeRequest
            // Access validated data safely
            $target = $request->validated('target');
            $signals = $request->validated('signals');
            $metadata = $request->validated('metadata');
            $dispatchId = $request->validated('dispatch_id');
            
            // Create target object for SDK
            $targetObject = new \PreludeSo\Sdk\Target(
                $target['value'],
                $target['type']
            );
            
            // Create signals object for SDK
            $signalsObject = new \PreludeSo\Sdk\Signals($signals);
            
            // Create metadata object for SDK if provided
            $metadataObject = null;
            if ($metadata) {
                $metadataObject = new \PreludeSo\Sdk\Metadata($metadata);
            }
            
            // Predict outcome using the SDK
            $result = Prelude::predictOutcome($targetObject, $signalsObject, $dispatchId, $metadataObject);
            
            return response()->json([
                'prediction_id' => $result->getId(),
                'outcome' => $result->getOutcome(),
                'confidence' => $result->getConfidence(),
                'target_type' => $target['type'],
                'has_signals' => !empty($signals),
                'has_metadata' => !empty($metadata),
                'dispatch_id' => $dispatchId,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Send feedback with comprehensive validation using SendFeedbackRequest.
     */
    public function sendFeedbackWithValidation(SendFeedbackRequest $request): JsonResponse
    {
        try {
            // All validation is handled by SendFeedbackRequest
            // Access validated data safely
            $feedbacks = $request->validated('feedbacks');
            
            // Create feedback objects for SDK
            $feedbackObjects = [];
            foreach ($feedbacks as $feedback) {
                // Create target object
                $targetObject = new \PreludeSo\Sdk\ValueObjects\Shared\Target(
                    $feedback['target']['value'],
                    $feedback['target']['type']
                );
                
                // Create signals object if provided
                $signalsObject = null;
                if (!empty($feedback['signals'])) {
                    $signalsObject = new \PreludeSo\Sdk\ValueObjects\Shared\Signals($feedback['signals']);
                }
                
                // Create metadata object if provided
                $metadataObject = null;
                if (!empty($feedback['metadata'])) {
                    $metadataObject = new \PreludeSo\Sdk\ValueObjects\Shared\Metadata($feedback['metadata']);
                }
                
                $feedbackObjects[] = new \PreludeSo\Sdk\ValueObjects\Watch\Feedback(
                    $targetObject,
                    $feedback['type'],
                    $signalsObject,
                    $feedback['dispatch_id'] ?? '',
                    $metadataObject
                );
            }
            
            // Send feedback using the SDK
            $result = Prelude::sendFeedback($feedbackObjects);
            
            return response()->json([
                'success' => $result->isSuccess(),
                'processed_count' => count($feedbackObjects),
                'feedbacks_sent' => array_map(function($feedback) {
                    return [
                        'target' => $feedback['target'],
                        'type' => $feedback['type'],
                        'has_signals' => !empty($feedback['signals']),
                        'has_metadata' => !empty($feedback['metadata']),
                    ];
                }, $feedbacks),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Lookup phone number information.
     */
    public function lookupPhoneNumber(LookupRequest $request): JsonResponse
    {
        try {
            // All validation is handled automatically
            $phoneNumber = $request->validated('phone_number');
            $type = $request->validated('type', []);
            
            // Lookup phone number using the SDK
            $result = Prelude::lookup()->lookup($phoneNumber, $type);
            
            return response()->json([
                'phone_number' => $result->getPhoneNumber(),
                'country_code' => $result->getCountryCode(),
                'line_type' => $result->getLineType()?->value,
                'caller_name' => $result->getCallerName(),
                'flags' => array_map(fn($flag) => $flag->value, $result->getFlags()),
                'network_info' => [
                    'carrier_name' => $result->getNetworkInfo()->getCarrierName(),
                    'mcc' => $result->getNetworkInfo()->getMcc(),
                    'mnc' => $result->getNetworkInfo()->getMnc(),
                ],
                'original_network_info' => [
                    'carrier_name' => $result->getOriginalNetworkInfo()->getCarrierName(),
                    'mcc' => $result->getOriginalNetworkInfo()->getMcc(),
                    'mnc' => $result->getOriginalNetworkInfo()->getMnc(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Example: Resend verification OTP.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        try {
            $verificationId = $request->input('verification_id');
            $result = Prelude::verification()->resend($verificationId);
            
            return response()->json([
                'verification_id' => $result->getId(),
                'status' => $result->getStatus(),
                'expires_at' => $result->getExpiresAt()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}