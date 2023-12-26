<?php
declare(strict_types=1);

namespace FileUpload\Storage;

abstract class StorageManager implements StorageManagerInterface
{
    protected array $configurations = [];

    public function __construct(array $configurations = [])
    {
        $this->configurations = $configurations;
    }

    public function getConfig(string $key, $default = ""): string
    {
        if ($key === null) {
            return $this->configurations;
        }

        return $this->configurations[$key] ?? $default;
    }
}