<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\StoredFileInterface;
use Psr\Http\Message\UploadedFileInterface;

interface StorageManagerInterface
{
    /**
     * @param \FileUpload\Storage\StorageConfigInterface $config Configuration
     */
    public function __construct(StorageConfigInterface $config);

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject UploadedFile Object
     * @return \FileUpload\File\StoredFileInterface
     */
    public function put(UploadedFileInterface $fileObject): StoredFileInterface;

    /**
     * @param string $fileName File name without slashes
     * @return \FileUpload\File\StoredFileInterface
     */
    public function pull(string $fileName): StoredFileInterface;
}
