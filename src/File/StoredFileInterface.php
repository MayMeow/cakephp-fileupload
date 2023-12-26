<?php
declare(strict_types=1);

namespace FileUpload\File;

interface StoredFileInterface
{
    public function getContent(): string;

    public function getMimeType(): string;
}
