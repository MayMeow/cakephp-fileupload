<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFileDecorator;
use Psr\Http\Message\UploadedFileInterface;

interface StorageManagerInterface
{
    public function __construct(array $configurations = []);

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject Uploaded file object
     */
    public function put(UploadedFileInterface $fileObject): UploadedFileDecorator;

    /**
     * @param string $fileName File name without slashes
     * @return \FileUpload\File\StoredFileInterface
     */
    public function pull(string $fileName): StoredFileInterface;
}
