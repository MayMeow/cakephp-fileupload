<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Http\Exception\HttpException;
use FileUpload\File\UploadedFileDecorator;
use FileUpload\Storage\StorageManagerInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Upload component
 */
class UploadComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var string[]
     */
    protected array $_defaultConfig = [
        'fieldName' => 'uploaded_file',
        'allowedFileTypes' => '*',
    ];

    /**
     * @var \FileUpload\Storage\StorageManagerInterface|null
     */
    private ?StorageManagerInterface $storageManager = null;

    /**
     * Uploading File to storage and returns info of that file
     *
     * @param \Cake\Controller\Controller $controller Controller instance
     * @throws \Cake\Http\Exception\HttpException When upload data is missing or misconfigured
     */
    public function getFile(Controller $controller): UploadedFileDecorator
    {
        $uploadedFile = $controller->getRequest()->getData($this->getConfig('fieldName'));

        if (!$uploadedFile instanceof UploadedFileInterface) {
            throw new HttpException('Uploaded file data is missing or invalid.');
        }

        $sm = $this->_getStorageManager();

        return $sm->put($uploadedFile);
    }

    protected function _getStorageManager(): StorageManagerInterface
    {
        if ($this->storageManager instanceof StorageManagerInterface) {
            return $this->storageManager;
        }

        $sm = $this->getConfig('managerClass');

        if (!is_string($sm) || $sm === '') {
            throw new HttpException('Storage manager class is not configured.');
        }

        if (!class_exists($sm)) {
            throw new HttpException(sprintf('Storage manager class "%s" does not exist.', $sm));
        }

        $storageManager = new $sm($this->getConfig());

        if (!$storageManager instanceof StorageManagerInterface) {
            throw new HttpException(sprintf('Storage manager must implement %s.', StorageManagerInterface::class));
        }

        $this->storageManager = $storageManager;

        return $this->storageManager;
    }
}
