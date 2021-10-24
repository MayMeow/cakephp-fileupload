<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\Exceptions\FileContentException;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class LocalStorageManager implements StorageManagerInterface
{
    /**
     * @var \FileUpload\Storage\StorageConfigInterface
     */
    protected $configuration;

    /**
     * @param \FileUpload\Storage\StorageConfigInterface $config Configuration for storage manager
     */
    public function __construct(StorageConfigInterface $config)
    {
        $this->configuration = $config;
    }

    /**
     * Upload file to storage
     *
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject Uploaded file object
     * @return \FileUpload\File\StoredFileInterface
     */
    public function put(UploadedFileInterface $fileObject): StoredFileInterface
    {
        $fileObject->moveTo($this->configuration->getConfig('storagePath') . $fileObject->getClientFilename());

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileObject->getClientFilename());
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));

        return $uploadedFile;
    }

    /**
     * Download file from storage
     *
     * @param string $fileName Filename without slashes
     * @return \FileUpload\File\StoredFileInterface
     * @throws \FileUpload\Exceptions\FileContentException
     */
    public function pull(string $fileName): StoredFileInterface
    {
        $fileContent = file_get_contents($this->configuration->getConfig('storagePath') . $fileName);

        if ($fileContent == false) {
            throw new FileContentException();
        }

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileName);
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));
        $uploadedFile->setFileContent($fileContent);

        return $uploadedFile;
    }
}
