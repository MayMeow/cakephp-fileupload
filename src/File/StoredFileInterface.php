<?php
declare(strict_types=1);

namespace FileUpload\File;

interface StoredFileInterface
{
    public function getFileType(): string;

    public function getStorageType(): string;

    public function getPath(): string;

    public function getFileName(): string;

    public function getFileContent(): string;
}
