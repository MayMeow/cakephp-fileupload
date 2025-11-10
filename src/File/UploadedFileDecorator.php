<?php
declare(strict_types=1);

namespace FileUpload\File;

use Psr\Http\Message\UploadedFileInterface;

class UploadedFileDecorator
{
    public function __construct(
        protected UploadedFileInterface $originalData,
        protected string $storageType,
        protected array $options = []
    ) {
    }

    public function getOriginalData(): UploadedFileInterface
    {
        return $this->originalData;
    }

    public function getStorageType(): string
    {
        return $this->storageType;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    public function getFileName(): string
    {
        $fileName = $this->get('fileName');

        if (is_string($fileName) && $fileName !== '') {
            return $fileName;
        }

        return $this->originalData->getClientFilename();
    }
}