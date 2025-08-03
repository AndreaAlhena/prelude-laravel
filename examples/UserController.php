<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use PreludeSo\Laravel\Facades\Prelude;
use PreludeSo\Sdk\PreludeClient;
use PreludeSo\Laravel\Traits\InteractsWithPrelude;

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