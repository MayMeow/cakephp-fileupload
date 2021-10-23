<?php

namespace FileUpload\Storage;

use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

interface StorageManagerInterface
{
    /**
     * @param StorageConfigInterface $config Configuration
     */
    public function __construct(StorageConfigInterface $config);

    /**
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject
     * @return \FileUpload\File\StoredFileInterface
     */
    public function put(UploadedFileInterface $fileObject) : StoredFileInterface;

    public function pull(string $fileName) : StoredFileInterface;
}
