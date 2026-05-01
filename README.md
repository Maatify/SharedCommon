# Maatify SharedCommon

[![Latest Version](https://img.shields.io/packagist/v/maatify/shared-common.svg?style=for-the-badge)](https://packagist.org/packages/maatify/shared-common)
[![PHP Version](https://img.shields.io/packagist/php-v/maatify/shared-common.svg?style=for-the-badge)](https://packagist.org/packages/maatify/shared-common)
[![License](https://img.shields.io/packagist/l/maatify/shared-common.svg?style=for-the-badge)](LICENSE)

![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-4E8CAE)

[![Changelog](https://img.shields.io/badge/Changelog-View-blue)](CHANGELOG.md)
[![Security](https://img.shields.io/badge/Security-Policy-important)](SECURITY.md)

![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/shared-common?label=Monthly%20Downloads&color=00A8E8)
![Total Downloads](https://img.shields.io/packagist/dt/maatify/shared-common?label=Total%20Downloads&color=2AA9E0)

![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-blueviolet?style=for-the-badge)

[![Install](https://img.shields.io/badge/Install-composer%20require-blue?style=for-the-badge)](https://packagist.org/packages/maatify/shared-common)

## Overview

The `Maatify\SharedCommon` module contains foundational contracts and abstractions that are intended to be shared across all Maatify modules (such as `AdminKernel`, `Verification`, etc.). Its primary goal is to provide unified interfaces for common cross-cutting concerns like time management, security contexts, permission mapping definitions, and telemetry, enabling consistent behavior and testing across the entire system.

## Purpose

By depending on `SharedCommon` rather than framework-specific implementations or raw PHP functions (like `time()` or `date()`), other modules can remain truly framework-agnostic. This module defines the "how we communicate" contracts for basic system realities.

## Module Structure

```md
Modules/SharedCommon/
├── Bootstrap/                 # Dependency Injection bindings
│   └── SharedCommonBindings.php
├── Contracts/                 # Core interfaces for time, telemetry, security, and shared module extensions
│   ├── ClockInterface.php
│   ├── Security/              # Framework-neutral security extension contracts
│   │   ├── PermissionMapProviderInterface.php
│   │   ├── PermissionRequirementDefinition.php
│   │   └── ProvidesPermissionMapsInterface.php
│   ├── SecurityEventContextInterface.php
│   └── TelemetryContextInterface.php
├── Infrastructure/            # Default implementations of contracts
│   └── SystemClock.php
├── Path/                      # Common application path resolution utilities
│   └── AppPaths.php
├── docs/                      # Architectural and integration documentation
└── composer.json              # Standalone package metadata
```

## Quick Usage

To quickly integrate the default implementations of this module into your dependency injection container:

```php
use Maatify\SharedCommon\Bootstrap\SharedCommonBindings;
use DI\ContainerBuilder;
use Maatify\SharedCommon\Contracts\ClockInterface;

$builder = new ContainerBuilder();

// Register the bindings for SharedCommon contracts
SharedCommonBindings::register($builder);

$container = $builder->build();

// Resolve the Clock
/** @var ClockInterface $clock */
$clock = $container->get(ClockInterface::class);

// Get the current time as a DateTimeImmutable object
$now = $clock->now();
echo $now->format('Y-m-d H:i:s');
```

---

## Permission Mapping Contracts

`SharedCommon` provides framework-neutral permission mapping contracts under:

```php
Maatify\SharedCommon\Contracts\Security
```

These contracts allow independent Maatify modules to expose route-to-permission requirements without depending on `AdminKernel` or any application-specific security implementation.

This keeps modules reusable and decoupled while allowing the application or kernel layer to aggregate permission maps and convert them into its own authorization model.

### Defining Permission Requirements

Use `PermissionRequirementDefinition` to describe the permission requirement for a route.

```php
use Maatify\SharedCommon\Contracts\Security\PermissionRequirementDefinition;

$single = PermissionRequirementDefinition::single('payment_methods.list');

$anyOf = PermissionRequirementDefinition::anyOf([
    'payment_methods.list',
    'payment_methods.dropdown',
]);

$allOf = PermissionRequirementDefinition::allOf([
    'payment_methods.update',
    'payment_methods.translations.upsert',
]);

$compound = PermissionRequirementDefinition::compound(
    anyOf: ['payment_methods.list', 'payment_methods.dropdown'],
    allOf: ['admin.access'],
);
```

### Providing a Permission Map from a Module

A module can expose its route permission map by implementing `PermissionMapProviderInterface`.

```php
<?php

declare(strict_types=1);

namespace Maatify\PaymentMethod\Security;

use Maatify\SharedCommon\Contracts\Security\PermissionMapProviderInterface;
use Maatify\SharedCommon\Contracts\Security\PermissionRequirementDefinition;

final class PaymentMethodPermissionMapProvider implements PermissionMapProviderInterface
{
    /**
     * @return array<string, PermissionRequirementDefinition>
     */
    public function permissionMap(): array
    {
        return [
            'payment_methods.list.ui' => PermissionRequirementDefinition::single('payment_methods.list'),
            'payment_methods.list.api' => PermissionRequirementDefinition::single('payment_methods.list'),

            'payment_methods.dropdown.api' => PermissionRequirementDefinition::anyOf([
                'payment_methods.list',
                'payment_methods.dropdown',
            ]),

            'payment_methods.create.api' => PermissionRequirementDefinition::single('payment_methods.create'),
            'payment_methods.update.api' => PermissionRequirementDefinition::single('payment_methods.update'),
        ];
    }
}
```

### Exposing Permission Map Providers from a Package

A package or module-level entry point may implement `ProvidesPermissionMapsInterface` to expose one or more permission map providers.

```php
<?php

declare(strict_types=1);

namespace Maatify\PaymentMethod;

use Maatify\PaymentMethod\Security\PaymentMethodPermissionMapProvider;
use Maatify\SharedCommon\Contracts\Security\PermissionMapProviderInterface;
use Maatify\SharedCommon\Contracts\Security\ProvidesPermissionMapsInterface;

final class PaymentMethodPackage implements ProvidesPermissionMapsInterface
{
    /**
     * @return list<PermissionMapProviderInterface>
     */
    public function permissionMapProviders(): array
    {
        return [
            new PaymentMethodPermissionMapProvider(),
        ];
    }
}
```

The consuming application or kernel layer is responsible for collecting these providers and converting the neutral definitions into its own authorization objects.

---

## Further Documentation

- [How to Use](HOW_TO_USE.md) - Practical integration instructions.
- [Changelog](CHANGELOG.md) - History and evolution of the module.

### Documentation Book

Comprehensive architecture and integration guides are available in the Book:

| Chapter | Description |
|---|---|
| [Table of Contents](docs/book/BOOK.md) | Main entry point for the documentation book. |
| [01. Overview](docs/book/01_overview.md) | The philosophy and purpose behind SharedCommon. |
| [02. Architecture](docs/book/02_architecture.md) | Layering and separation of interfaces and infrastructure. |
| [03. Domain Objects](docs/book/03_domain_objects.md) | Core contracts representing the system state context. |
| [04. Clock Abstraction](docs/book/04_clock_abstraction.md) | Why the ClockInterface is crucial for testability and consistency. |
| [05. Integration Patterns](docs/book/05_integration_patterns.md) | Real-world DI container wiring and cross-module usage. |
| [06. Extension Points](docs/book/06_extension_points.md) | How to provide application-specific context implementations. |
