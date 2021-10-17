<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\HttpException;
use Cake\Http\ServerRequest;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Upload component
 */
class UploadComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fieldName' => 'uploaded_file',
        'storagePath' => ROOT . DS . 'storage' . DS,
        'allowedFileTypes' => '*',
    ];

    /**
     * @param \Cake\Http\ServerRequest $serverRequest Server Request
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    public function getFile(ServerRequest $serverRequest): UploadedFileInterface
    {
        /** @var \Psr\Http\Message\UploadedFileInterface $fileObject */
        $fileObject = $serverRequest->getData($this->getConfig('fieldName'));

        if ($this->_isAllowedFileType($fileObject->getClientMediaType())) {
            $fileObject->moveTo($this->getConfig('storagePath') . $fileObject->getClientFilename());

            return $fileObject;
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
