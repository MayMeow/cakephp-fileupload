<?php

namespace FileUpload\Storage;

use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class LocalStorageManager implements StorageManagerInterface
{
    /**
     * @var StorageConfigInterface
     */
    protected $configuration;

    public function __construct(StorageConfigInterface $config)
    {
        $this->configuration = $config;
    }

    public function put(UploadedFileInterface $fileObject) : StoredFileInterface
    {
        $fileObject->moveTo($this->configuration->getConfig('storagePath') . $fileObject->getClientFilename());

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileObject->getClientFilename());
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));

        return $uploadedFile;
    }
}
