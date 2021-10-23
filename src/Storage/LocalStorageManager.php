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

    /**
     * @param UploadedFileInterface $fileObject
     * @return StoredFileInterface
     */
    public function put(UploadedFileInterface $fileObject) : StoredFileInterface
    {
        $fileObject->moveTo($this->configuration->getConfig('storagePath') . $fileObject->getClientFilename());

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileObject->getClientFilename());
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));

        return $uploadedFile;
    }

    /**
     * @param string $fileName
     * @return StoredFileInterface
     */
    public function pull(string $fileName): StoredFileInterface
    {
        $fileContent = file_get_contents($this->configuration->getConfig('storagePath') . $fileName);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileName);
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));
        $uploadedFile->setFileContent($fileContent);

        return $uploadedFile;
    }
}
