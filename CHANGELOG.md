# Changelog

All notable changes to the `Maatify\SharedCommon` module will be documented in this file.

The format is based on **Keep a Changelog** and this project follows **Semantic Versioning (SemVer)**.

---

## [1.0.0] - 2026-03-11

### Added

- **Bootstrap Layer:** Introduced `SharedCommonBindings` in the `Maatify\SharedCommon\Bootstrap` namespace to provide a standardized way to register `ClockInterface` with the default `SystemClock` implementation in PHP-DI containers.

- **Standalone Package Preparation:** Added a `composer.json` file to define `maatify/shared-common` as a standalone library, including PHP 8.2 requirements and PSR-4 autoloading configuration.

- **Comprehensive Documentation:** Introduced full documentation for the module including:
  - `README.md`
  - `HOW_TO_USE.md`
  - `CHANGELOG.md`
  - a complete architecture and integration documentation book under `docs/book/`.

### Changed

- **Module Packaging Preparation:** Refactored the module structure and metadata to support extraction into an independent reusable package (`maatify/shared-common`) while keeping the current application behavior unchanged.

---

## [Unreleased]

Future changes will be documented here.
