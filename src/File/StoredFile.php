<?php
declare(strict_types=1);

namespace FileUpload\File;

class StoredFile implements StoredFileInterface
{
    public function __construct(
        protected string $file,
    ) {
    }

    public function getContent(): string    
    {
        $stream = fopen($this->file, 'rb');
        return stream_get_contents($stream);
    }

    public function getMimeType(): string
    {
        return mime_content_type($this->file);
    }
}