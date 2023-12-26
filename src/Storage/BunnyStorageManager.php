<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\PathUtils;
use FileUpload\File\StoredFile;
use FileUpload\File\StoredFileInterface;
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
        $accessKey = $this->getConfig('accessKey');
        $hostname = (!empty($this->getConfig('region'))) ? $this->getConfig('region') . '.' . $this->getConfig('baseHostName') : $this->getConfig('baseHostName');

        $fileName = PathUtils::fileNameSanitize($fileObject->getClientFilename());

        $url = $this->composeUrl($hostname, $this->getConfig('storageZone'), $this->getConfig('storageZonePath'), $fileName);

        $ch = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PUT => true,
            CURLOPT_INFILE => fopen($fileObject->getStream()->getMetadata('uri'), 'r'),
            CURLOPT_INFILESIZE => $fileObject->getSize(),
            CURLOPT_HTTPHEADER => array(
                "AccessKey: {$accessKey}",
                'Content-Type: application/octet-stream'
            )
        ];

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        if (isset($response['HttpCode']) && $response['HttpCode'] !== 201) {
            throw new \RuntimeException('Failed to upload file to BunnyCDN');
        }

        $file = new UploadedFileDecorator($fileObject, self::STORAGE_TYPE, [
            'storagePath' => $this->composeUrl($this->getConfig('cdnDomain'), $this->getConfig('storageZonePath')),
            'fileName' => $fileName,
        ]);

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
        $url = $this->composeUrl($this->getConfig('cdnDomain'), $this->getConfig('storageZonePath'), $fileName);
        return new StoredFile(file: $url);
    }

    public function composeUrl(string ...$url): string
    {
        $url = array_filter($url, fn ($part) => !empty($part) && $part !== '');

        $https = 'https://';
        return $https .  implode('/', $url);
    }
}