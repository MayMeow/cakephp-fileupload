<?php
declare(strict_types=1);

namespace FileUpload\File;

use Laminas\Diactoros\UploadedFile;

class UploadedFileDecorator
{
    public function __construct(
        protected UploadedFile $originalData,
        protected string $storageType,
        protected array $options = []
    ) {
    }

    public function getOriginalData(): UploadedFile
    {
        return $this->originalData;
    }

    public function getStorageType(): string
    {
        return $this->storageType;
    }

    public function get(string $key): string
    {
        return $this->options[$key] ?? "";
    }
}