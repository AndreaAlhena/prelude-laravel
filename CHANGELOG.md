# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.1] - 2024-12-19

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