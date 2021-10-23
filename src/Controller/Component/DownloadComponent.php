<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use FileUpload\File\StoredFileInterface;
use FileUpload\Storage\LocalStorageManager;
use FileUpload\Storage\S3StorageManager;
use FileUpload\Storage\StorageConfigInterface;

/**
 * Download component
 */
class DownloadComponent extends Component implements StorageConfigInterface
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fieldName' => 'uploaded_file',
        'storagePath' => ROOT . DS . 'storage' . DS ,
        'allowedFileTypes' => '*',
        'storage_type' => 'local',
    ];

    public function getFile(string $fileName): StoredFileInterface
    {
        /** check if storage is s3. Local storage is default one */
        if ($this->getConfig('storage_type') == 's3') {
            $sm = new S3StorageManager($this);
        } else {
            $sm = new LocalStorageManager($this); // default one
        }

        return $sm->pull($fileName);
    }
}
