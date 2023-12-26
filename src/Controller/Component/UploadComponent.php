<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Http\Exception\HttpException;
use FileUpload\File\UploadedFileDecorator;
use FileUpload\Storage\StorageManagerInterface;

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
     * Allowed stoage types
     *
     * @var string[]
     */
    protected array $_allowedStorageTypes = [
        'local', 's3',
    ];

    /**
     * Uploading File to storage and returns info of that file
     *
     * @param \Cake\Http\ServerRequest $serverRequest Server Request
     * @throws HttpException
     */
    public function getFile(Controller $controller): UploadedFileDecorator
    {
        $sm = $this->_getStorageManager();

        return $sm->put($controller->getRequest()->getData($this->getConfig('fieldName')));
    }

    protected function _getStorageManager(): StorageManagerInterface
    {
        $sm = $this->getConfig('managerClass');

        return new $sm($this->getConfig());
    }
}
