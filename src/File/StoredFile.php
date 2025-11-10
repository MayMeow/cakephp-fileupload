<?php
declare(strict_types=1);

namespace FileUpload\File;

use FileUpload\Exceptions\FileContentException;

class StoredFile implements StoredFileInterface
{
    private ?string $content = null;

    private ?string $mimeType = null;

    public function __construct(
        private string $file,
    ) {
    }

    public function getContent(): string
    {
        if ($this->content !== null) {
            return $this->content;
        }

        $content = @file_get_contents($this->file);

        if ($content === false) {
            throw new FileContentException(sprintf('Cannot load content of file "%s"', $this->file));
        }

        $this->content = $content;
        $this->mimeType = $this->detectMimeType($content);

        return $this->content;
    }

    public function getMimeType(): string
    {
        if ($this->mimeType === null) {
            $this->mimeType = $this->detectMimeType($this->getContent());
        }

        return $this->mimeType;
    }

    private function detectMimeType(string $content): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            return 'application/octet-stream';
        }

        $mimeType = finfo_buffer($finfo, $content) ?: 'application/octet-stream';
        finfo_close($finfo);

        return $mimeType;
    }
}