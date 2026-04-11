<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Path;

final class AppPaths
{
    private string $root;

    public function __construct(string $rootPath)
    {
        $this->root = rtrim($rootPath, '/');
    }

    public function root(): string
    {
        return $this->root;
    }

    public function publicPath(): string
    {
        return $this->root . '/public';
    }

    public function publicImages(): string
    {
        return $this->root . '/public/images';
    }

    public function storage(): string
    {
        return $this->root . '/storage';
    }

    public function config(): string
    {
        return $this->root . '/config';
    }

    public function logs(): string
    {
        return $this->root . '/storage/logs';
    }

    public function storagePath(string $subfolder): string
    {
        return $this->storage() . '/' . trim($subfolder, '/');
    }
}
