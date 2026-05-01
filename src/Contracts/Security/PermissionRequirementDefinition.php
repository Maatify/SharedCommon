<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Contracts\Security;

final readonly class PermissionRequirementDefinition
{
    /**
     * @param list<string> $anyOf
     * @param list<string> $allOf
     */
    private function __construct(
        public array $anyOf,
        public array $allOf,
    ) {
        if ($this->anyOf === [] && $this->allOf === []) {
            throw new \InvalidArgumentException('Permission requirement cannot be empty.');
        }

        foreach ($this->anyOf as $permission) {
            self::assertValidPermission($permission);
        }

        foreach ($this->allOf as $permission) {
            self::assertValidPermission($permission);
        }
    }

    public static function single(string $permission): self
    {
        self::assertValidPermission($permission);

        return new self([$permission], []);
    }

    /**
     * @param list<string> $permissions
     */
    public static function anyOf(array $permissions): self
    {
        self::assertValidPermissionList($permissions, 'anyOf');

        return new self($permissions, []);
    }

    /**
     * @param list<string> $permissions
     */
    public static function allOf(array $permissions): self
    {
        self::assertValidPermissionList($permissions, 'allOf');

        return new self([], $permissions);
    }

    /**
     * @param list<string> $anyOf
     * @param list<string> $allOf
     */
    public static function compound(array $anyOf = [], array $allOf = []): self
    {
        if ($anyOf !== []) {
            self::assertValidPermissionList($anyOf, 'anyOf');
        }

        if ($allOf !== []) {
            self::assertValidPermissionList($allOf, 'allOf');
        }

        return new self($anyOf, $allOf);
    }

    /**
     * @return array{anyOf: list<string>, allOf: list<string>}
     */
    public function toArray(): array
    {
        return [
            'anyOf' => $this->anyOf,
            'allOf' => $this->allOf,
        ];
    }

    private static function assertValidPermission(string $permission): void
    {
        if (trim($permission) === '') {
            throw new \InvalidArgumentException('Permission name cannot be empty.');
        }
    }

    /**
     * @param list<string> $permissions
     */
    private static function assertValidPermissionList(array $permissions, string $field): void
    {
        if ($permissions === []) {
            throw new \InvalidArgumentException(sprintf('Permission list "%s" cannot be empty.', $field));
        }

        foreach ($permissions as $permission) {
            self::assertValidPermission($permission);
        }
    }
}
