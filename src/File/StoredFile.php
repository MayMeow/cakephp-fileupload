<?php
declare(strict_types=1);

namespace FileUpload\File;

class StoredFile implements StoredFileInterface
{
    protected string $mimeType;
    public function __construct(
        protected string $file,
    ) {
    }

    public function getContent(): string    
    {
        $stream = fopen($this->file, 'rb');
        $content = stream_get_contents($stream);

        $finfo = finfo_open();
        $this->mimeType = finfo_buffer($finfo, $content, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        return $content;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}