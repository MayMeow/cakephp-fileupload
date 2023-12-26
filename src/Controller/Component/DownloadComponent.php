<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use FileUpload\File\StoredFileInterface;
use FileUpload\Storage\StorageManagerInterface;

/**
 * Download component
 */
class DownloadComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected array $_defaultConfig = [
    ];

    /**
     * Returns stored file info and content
     *
     * @param string $fileName Name of file without path or slashes
     * @return \FileUpload\File\StoredFileInterface Stored file object
     */
    public function getFile(string $fileName): StoredFileInterface
    {
        $sm = $this->_getStorageManager();

        return $sm->pull($fileName);
    }

    protected function _getStorageManager(): StorageManagerInterface
    {
        $sm = $this->getConfig('managerClass');

        return new $sm($this->getConfig());
    }
}
