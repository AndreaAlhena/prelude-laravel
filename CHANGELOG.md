# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `CreateVerificationRequest` class extending Laravel's FormRequest with comprehensive validation rules
- `CheckVerificationRequest` class for validating verification check parameters
- `PredictOutcomeRequest` class for validating outcome prediction parameters
- `SendTransactionalRequest` class for validating transactional message send parameters
- Complete validation support for all Prelude SDK parameters:
  - Target validation (phone number and email with type-specific validation)
  - Signals validation (browser/device information for fraud detection)
  - Options validation (verification configuration like expiry, code length, channel)
  - Metadata validation (custom tracking and user data)
  - Dispatch ID validation (frontend SDK integration)
  - Code validation (verification code with length constraints)
  - Transactional options validation (channel, priority, scheduling, callbacks, variables)
- Built-in phone number validation with international format support
- Email address validation for email-based verifications
- `CreateOtpRequest` example demonstrating CreateVerificationRequest customization
- Example usage of `CheckVerificationRequest` in UserController
- Example usage of `PredictOutcomeRequest` in UserController with comprehensive validation
- Example usage of `SendTransactionalRequest` in UserController with comprehensive validation
- Comprehensive test suite with actual data validation scenarios
- Test coverage for both valid and invalid data cases across all validation rules
- Support for `illuminate/foundation` package

### Changed
- Enhanced FormRequest implementation with full SDK parameter support
- Updated validation structure from simple phone_number to comprehensive Target object
- Removed complex validation error logging and configuration options
- Updated Laravel compatibility to support Laravel 9.0+ (previously 12.0+ only)
- Updated PHP requirement to ^8.1 (previously ^8.2)
- Updated illuminate/support to support ^9.0|^10.0|^11.0|^12.0
- Updated orchestra/testbench to support ^7.0|^8.0|^9.0|^10.0
- Updated Pest dependencies to support both v2 and v3
- Removed redundant laravel/framework and illuminate/foundation dependencies
- Removed public `prelude()` method from `InteractsWithPrelude` trait to improve encapsulation

## [1.0.1] - 2025-08-04

### Added
- Laravel integration package for Prelude SDK
- Service provider with auto-discovery support
- Prelude facade for easy access
- Console command for package installation
- Configuration file publishing
- Comprehensive test suite with Pest
- Docker support for development
- GitHub Actions workflow for automated testing
- Code coverage reporting with Codecov

### Features
- Auto-registration of service provider via Laravel package discovery
- Singleton binding of PreludeClient
- Configuration merging with sensible defaults
- Trait for easy integration with models/classes
- Support for Laravel 12.0+
- PHP 8.2+ compatibility

### Developer Experience
- Complete Docker development environment
- Makefile for common development tasks
- Pre-commit hooks with Husky
- Comprehensive documentation
- Example usage in UserController

[Unreleased]: https://github.com/prelude-so/laravel/compare/v1.0.1...HEAD
[1.0.1]: https://github.com/prelude-so/laravel/releases/tag/v1.0.1