# General
1. When a new feature is added, prepare a test suite
2. When a feature is removed, be sure to remove the unuseful related tests
3. When a feature is modified, update the test suite accordingly
4. Run tests before and after the change to ensure everything works as expected
5. Run commands in the Docker container - never outside

# Coding style
1. Always separate declarative blocks from imperative blocks
2. Always use strict typing

## Properties and Methods naming conventions
1. Private properties and methods should always be prefixed with an underscore
2. Properties and methods should always be sorted alphabetically
3. Properties that are optional should have a proper default value. In example, null for objects, empty string for strings and 0 for numbers (when possible)

## Arrays
1. Sort associative array keys in alphabetical order

## Enums
1. Sort enum cases alphabetically

## Import statements
1. Always remove unused import statements
2. Sort import statements alphabetically
3. Group import statements by type (e.g. core PHP, external libraries, internal classes)
4. Group import statements by namespace (e.g. Symfony components, Laravel facades, custom classes)