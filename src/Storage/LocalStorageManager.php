<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\StoredFile;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFileDecorator;
use Psr\Http\Message\UploadedFileInterface;

class LocalStorageManager extends StorageManager
{
    public const STORAGE_TYPE = 'local';

    /**
     * Upload file to storage
     *
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject Uploaded file object
     */
    public function put(UploadedFileInterface $fileObject): UploadedFileDecorator
    {   
        $fileObject->moveTo($this->getConfig('storagePath') . $fileObject->getClientFilename());

        $uploadedFile = new UploadedFileDecorator($fileObject, self::STORAGE_TYPE, options: [
            'storagePath' => $this->getConfig('storagePath'),
        ]);

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
        $file = new StoredFile(file: $this->getConfig('storagePath') . $fileName);

        return $file;
    }
}
