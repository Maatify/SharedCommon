<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Contracts\Security;

interface PermissionMapProviderInterface
{
    /**
     * @return array<string, PermissionRequirementDefinition>
     */
    public function permissionMap(): array;
}
