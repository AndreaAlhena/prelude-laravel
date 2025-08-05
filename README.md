# Prelude Laravel Package
[![codecov](https://codecov.io/gh/AndreaAlhena/prelude-laravel/graph/badge.svg?token=NUMXNAHLQR)](https://codecov.io/gh/AndreaAlhena/prelude-laravel)
[![Made with Trae](https://img.shields.io/badge/Made%20with-Trae%20AI-blueviolet?style=flat&color=32F08B)](https://trae.ai)

A Laravel integration package for the [prelude-so/sdk](https://github.com/prelude-so/sdk), providing seamless integration of Prelude services into your Laravel applications.

## Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 9.0 or higher (supports Laravel 9, 10, and 11)
- **Composer**: 2.0 or higher

## Installation

You can install the package via Composer:

```bash
composer require prelude-so/laravel
```

## Configuration

### Automatic Setup

Run the install command to automatically set up the package:

```bash
php artisan prelude:install
```

This command will:
- Publish the configuration file
- Add environment variables to your `.env` file
- Provide setup instructions

### Manual Setup

If you prefer manual setup:

1. Publish the configuration file:

```bash
php artisan vendor:publish --tag=prelude-config
```

2. Add your Prelude API credentials to your `.env` file:

```env
PRELUDE_API_KEY=your-api-key-here
PRELUDE_BASE_URL=https://api.prelude.so
PRELUDE_TIMEOUT=30
```

3. Configure the package in `config/prelude.php` as needed.

## Usage

### Using the Facade

The package provides a convenient facade for accessing Prelude services:

```php
use PreludeSo\Laravel\Facades\Prelude;

// Create a phone verification
$verification = Prelude::verification()->create('+1234567890');

// Check verification OTP
$result = Prelude::verification()->check($verificationId, '123456');

// Lookup phone number information
$lookup = Prelude::lookup()->phoneNumber('+1234567890');

// Send transactional message
$message = Prelude::transactional()->send('+1234567890', 'Your verification code is 123456');
```

### Dependency Injection

You can also inject the `PreludeClient` directly into your classes:

```php
use PreludeSo\Sdk\PreludeClient;

class UserController extends Controller
{
    public function createVerification(Request $request, PreludeClient $prelude)
    {
        $phoneNumber = $request->input('phone_number');
        $verification = $prelude->verification()->create($phoneNumber);
        
        return response()->json([
            'verification_id' => $verification->getId(),
            'status' => $verification->getStatus()
        ]);
    }
    
    public function checkVerification(Request $request, PreludeClient $prelude)
    {
        $verificationId = $request->input('verification_id');
        $code = $request->input('code');
        
        $result = $prelude->verification()->check($verificationId, $code);
        
        return response()->json([
            'success' => $result->isSuccess(),
            'status' => $result->getStatus()->value
        ]);
    }
}
```

#### SendTransactionalRequest

For sending transactional messages with comprehensive validation:

```php
use PreludeSo\Laravel\Http\Requests\SendTransactionalRequest;

class MessageController extends Controller
{
    public function sendTransactional(SendTransactionalRequest $request)
    {
        // All validation is handled automatically
        $to = $request->validated('to');
        $templateId = $request->validated('template_id');
        $options = $request->validated('options');
        $metadata = $request->validated('metadata');
        
        // Create options object for SDK if provided
        $optionsObject = null;
        if ($options) {
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
        ]);
    }
}
```

### Complete Examples

For complete working examples, see the [`examples/UserController.php`](examples/UserController.php) file which demonstrates:
- Phone verification creation and checking
- Phone number lookup
- Transactional messaging
- Error handling
- Both Facade and dependency injection patterns

### Using the Trait

For models or other classes that frequently interact with Prelude, use the provided trait:

```php
use PreludeSo\Laravel\Traits\InteractsWithPrelude;

class VerificationService
{
    use InteractsWithPrelude;
    
    public function createVerification(string $phoneNumber)
    {
        return $this->createVerification($phoneNumber);
    }
    
    public function checkVerification(string $verificationId, string $code)
    {
        return $this->checkVerification($verificationId, $code);
    }
    
    public function lookupPhone(string $phoneNumber)
    {
        return $this->lookupPhoneNumber($phoneNumber);
    }
    
    public function sendMessage(string $phoneNumber, string $message)
    {
        return $this->sendTransactionalMessage($phoneNumber, $message);
    }
}
```

### Form Requests with Prelude Integration

The package provides Form Request classes that extend Laravel's FormRequest with comprehensive validation rules for Prelude SDK parameters:

#### CreateVerificationRequest

For creating verifications with full parameter validation:

```php
use PreludeSo\Laravel\Http\Requests\CreateVerificationRequest;

// Use the base class directly with all validation rules
$request = new CreateVerificationRequest();

// Or extend it for custom requirements
class CreateOtpRequest extends CreateVerificationRequest
{
    public function rules(): array
    {
        // Option 1: Use all parent rules
        return parent::rules();
        
        // Option 2: Override with custom rules
        return [
            'target.type' => 'required|string|in:phone_number',
            'target.value' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/',
            'signals' => 'nullable|array',
            'options' => 'nullable|array',
            'metadata' => 'nullable|array',
            'dispatch_id' => 'nullable|string',
        ];
    }
}
```

#### CheckVerificationRequest

For checking verification codes with target and code validation:

```php
use PreludeSo\Laravel\Http\Requests\CheckVerificationRequest;

class VerificationController extends Controller
{
    public function checkVerification(CheckVerificationRequest $request)
    {
        // All validation is handled automatically
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
            'status' => $result->getStatus()->value
        ]);
    }
}
```

#### PredictOutcomeRequest

For predicting outcomes with comprehensive validation:

```php
use PreludeSo\Laravel\Http\Requests\PredictOutcomeRequest;

class PredictionController extends Controller
{
    public function predictOutcome(PredictOutcomeRequest $request)
    {
        // All validation is handled automatically
        $target = $request->validated('target');
        $signals = $request->validated('signals');
        $metadata = $request->validated('metadata');
        $dispatchId = $request->validated('dispatch_id');
        
        // Create objects for SDK
        $targetObject = new \PreludeSo\Sdk\Target(
            $target['value'],
            $target['type']
        );
        
        $signalsObject = new \PreludeSo\Sdk\Signals($signals);
        
        $metadataObject = null;
        if ($metadata) {
            $metadataObject = new \PreludeSo\Sdk\Metadata($metadata);
        }
        
        // Predict outcome using the SDK
        $result = Prelude::predictOutcome($targetObject, $signalsObject, $dispatchId, $metadataObject);
        
        return response()->json([
            'prediction_id' => $result->getId(),
            'outcome' => $result->getOutcome(),
            'confidence' => $result->getConfidence()
        ]);
    }
}
```

#### Supported Validation Parameters

**CreateVerificationRequest** includes validation rules for all SDK parameters:

- **Target** (required): Phone number or email validation
  - `target.type`: Must be 'phone_number' or 'email_address'
  - `target.value`: Validated based on the target type

- **Signals** (optional): Browser/device information
  - `signals.ip_address`: Valid IP address
  - `signals.user_agent`: Browser user agent string
  - `signals.device_fingerprint`: Device identification
  - And more browser-related fields

- **Options** (optional): Verification configuration
  - `options.template`: Custom message template
  - `options.expiry_minutes`: OTP expiration time (1-60 minutes)
  - `options.code_length`: OTP length (4-10 digits)
  - `options.channel`: Delivery method (sms, voice, email, whatsapp)
  - And more configuration options

- **Metadata** (optional): Custom tracking data
  - `metadata.user_id`: Your internal user ID
  - `metadata.source`: Request source identifier
  - `metadata.campaign_id`: Marketing campaign tracking
  - `metadata.custom_fields`: Additional custom data

- **Dispatch ID** (optional): Frontend SDK integration
  - `dispatch_id`: ID from Prelude's JavaScript SDK for enhanced fraud detection

**CheckVerificationRequest** includes validation rules for verification checking:

- **Target** (required): Phone number or email validation
  - `target.type`: Must be 'phone_number' or 'email_address'
  - `target.value`: Validated based on the target type

- **Code** (required): Verification code validation
  - `code`: String with length between 4-10 characters

**PredictOutcomeRequest** includes validation rules for outcome prediction:

- **Target** (required): Phone number or email validation
  - `target.type`: Must be 'phone_number' or 'email_address'
  - `target.value`: Validated based on the target type

- **Signals** (required): Browser/device information for fraud detection
  - `signals.ip_address`: Valid IP address
  - `signals.user_agent`: Browser user agent string
  - `signals.device_fingerprint`: Device identification
  - `signals.browser_plugins`: Array of browser plugins
  - `signals.screen_resolution`: Screen resolution information
  - `signals.timezone`: User timezone
  - `signals.language`: User language preference
  - `signals.session_id`: Session identifier
  - `signals.timestamp`: Request timestamp

- **Metadata** (optional): Custom tracking data
  - `metadata.user_id`: Your internal user ID
  - `metadata.source`: Request source identifier
  - `metadata.campaign_id`: Marketing campaign tracking
  - `metadata.reference_id`: Reference identifier
  - `metadata.custom_fields`: Additional custom data

- **Dispatch ID** (optional): Frontend SDK integration
  - `dispatch_id`: ID from Prelude's JavaScript SDK for enhanced fraud detection

**SendTransactionalRequest** includes validation rules for transactional messaging:

- **To** (required): Recipient validation
  - `to`: Valid phone number (international format, 7-15 digits) or email address

- **Template ID** (required): Message template
  - `template_id`: String identifier for the message template (max 255 characters)

- **Options** (optional): Message configuration
  - `options.from`: Sender identifier
  - `options.channel`: Delivery method (sms, email, whatsapp, voice)
  - `options.priority`: Message priority (low, normal, high)
  - `options.scheduled_at`: Schedule message for future delivery
  - `options.callback_url`: URL for delivery status callbacks
  - `options.webhook_url`: URL for webhook notifications
  - `options.variables`: Template variables for personalization
  - `options.attachments`: File attachments (URLs)
  - `options.reply_to`: Reply-to email address
  - `options.subject`: Email subject line
  - `options.tags`: Message tags for categorization

- **Metadata** (optional): Custom tracking data
  - `metadata.user_id`: Your internal user ID
  - `metadata.source`: Request source identifier
  - `metadata.campaign_id`: Marketing campaign tracking
  - `metadata.reference_id`: External reference ID
  - `metadata.custom_fields`: Additional custom data

## Configuration Options

The configuration file (`config/prelude.php`) supports the following options:

- `api_key`: Your Prelude API key
- `base_url`: The base URL for the Prelude API
- `timeout`: Request timeout in seconds

- `defaults`: Default options for SDK operations

## Development Environment

### Docker Setup (Recommended)

For a consistent development environment, use Docker:

```bash
# Build and start the environment
make build
make up

# Install dependencies
make install

# Run tests
make test
```

See [DOCKER.md](DOCKER.md) for detailed Docker setup instructions.

### Local Setup

Alternatively, set up locally with PHP 8.1+ and Composer:

```bash
composer install
```

## Testing

This package uses [Pest](https://pestphp.com/) for testing.

### With Docker:
```bash
make test                # Run tests
make test-coverage      # Run with coverage
make test-watch         # Run in watch mode
```

### Local Testing:
```bash
composer test           # Run tests
composer test-coverage  # Run with coverage
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.