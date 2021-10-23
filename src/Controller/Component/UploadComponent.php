<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Aws\S3\S3Client;
use Cake\Controller\Component;
use Cake\Http\Exception\HttpException;
use Cake\Http\ServerRequest;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use FileUpload\Storage\LocalStorageManager;
use FileUpload\Storage\S3StorageManager;
use FileUpload\Storage\StorageConfigInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Upload component
 */
class UploadComponent extends Component implements StorageConfigInterface
{
    /**
     * Default configuration.
     *
     * @var string[]
     */
    protected $_defaultConfig = [
        'fieldName' => 'uploaded_file',
        'storagePath' => 'cake.upload',
        'allowedFileTypes' => '*',
        'storage_type' => "s3"
    ];

    /**
     * Allowed storage types
     *
     * @var string[]
     */
    protected $_allowedStorageTypes = [
        'local', 's3'
    ];

    /**
     * @param \Cake\Http\ServerRequest $serverRequest Server Request
     * @return \FileUpload\File\StoredFileInterface
     * @throws \HttpException
     */
    public function getFile(ServerRequest $serverRequest): StoredFileInterface
    {
        if (!in_array($this->getConfig('storage_type'), $this->_allowedStorageTypes)) {
            throw new \HttpException('Not allowed storage type');
        }

        if ($this->getConfig('storage_type') == 's3') {
            $sm = new S3StorageManager($this);
        } else {
            $sm = new LocalStorageManager($this); // default one
        }

        /** @var \Psr\Http\Message\UploadedFileInterface $fileObject */
        $fileObject = $serverRequest->getData($this->getConfig('fieldName'));

        if ($this->_isAllowedFileType($fileObject->getClientMediaType())) {
            $storedFile = $sm->put($fileObject);

            return $storedFile;
        } else {
            throw new HttpException('Media type is not allowed');
        }
    }

    /**
     * @param string $fileType Type of the file
     * @return bool
     */
    private function _isAllowedFileType(string $fileType): bool
    {
        if ($this->getConfig('allowedFileTypes') == '*') {
            return true;
        }

        if (is_array($this->getConfig('allowedFileTypes'))) {
            if (in_array($fileType, $this->getConfig('allowedFileTypes'))) {
                return true;
            }
        }

        return false;
    }
}
