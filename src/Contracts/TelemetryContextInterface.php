<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Contracts;

/**
 * Telemetry context contract (request-scoped data source).
 *
 * Notes:
 * - Module contract only (library-friendly).
 * - Concrete implementations may come from HTTP layer (e.g., RequestContext).
 */
interface TelemetryContextInterface
{
    public function getRequestId(): ?string;

    public function getRouteName(): ?string;

    public function getIpAddress(): ?string;

    public function getUserAgent(): ?string;
}
