<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Contracts\Security;

interface ProvidesPermissionMapsInterface
{
    /**
     * @return list<PermissionMapProviderInterface>
     */
    public function permissionMapProviders(): array;
}
