<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\StoredFile;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use FileUpload\File\UploadedFileDecorator;
use Psr\Http\Message\UploadedFileInterface;

final class BunnyStorageManager extends StorageManager
{
    public const STORAGE_TYPE = 'bunny';

    /**
     * Upload file to storage
     *
     * @param \Psr\Http\Message\UploadedFileInterface $fileObject Uploaded file object
     * @return \FileUpload\File\StoredFileInterface
     */
    public function put(UploadedFileInterface $fileObject): UploadedFileDecorator
    {
        $file = new UploadedFileDecorator($fileObject, self::STORAGE_TYPE);

        return $file;
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
        return new StoredFile(file: $fileName);
    }
}