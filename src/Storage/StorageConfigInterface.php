<?php
declare(strict_types=1);

namespace FileUpload\Storage;

interface StorageConfigInterface
{
    /**
     * @param string|null $key The key to get or null for the whole config.
     * @param mixed $default The return value when the key does not exist.
     * @return mixed Configuration data at the named key or null if the key does not exist.
     */
    public function getConfig(?string $key = null, $default = null);
}
