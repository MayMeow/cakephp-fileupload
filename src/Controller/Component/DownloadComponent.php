<?php
declare(strict_types=1);

namespace FileUpload\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\HttpException;
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
    protected array $_defaultConfig = [];

    private ?StorageManagerInterface $storageManager = null;

    /**
     * Returns stored file info and content
     *
     * @param string $fileName Name of file without path or slashes
     * @return \FileUpload\File\StoredFileInterface Stored file object
     * @throws \Cake\Http\Exception\HttpException When storage manager is misconfigured
     */
    public function getFile(string $fileName): StoredFileInterface
    {
        return $this->_getStorageManager()->pull($fileName);
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
