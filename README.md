# Prelude Laravel Package

A Laravel integration package for the [prelude-so/sdk](https://github.com/prelude-so/sdk), providing seamless integration of Prelude services into your Laravel applications.

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

2. Copy the example environment file and add your Prelude API credentials:

```bash
cp .env.example .env
```

Then update your `.env` file with your actual credentials:

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



## Configuration Options

The configuration file (`config/prelude.php`) supports the following options:

- `api_key`: Your Prelude API key
- `base_url`: The base URL for the Prelude API
- `timeout`: Request timeout in seconds
- `defaults`: Default options for SDK operations

## Testing

This package uses [Pest](https://pestphp.com/) for testing.

Run the package tests:

```bash
composer test
```

Or with Pest directly:

```bash
./vendor/bin/pest
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email security@prelude.so instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Prelude Team](https://github.com/prelude-so)
- [All Contributors](../../contributors)