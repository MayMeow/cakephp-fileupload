<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use FileUpload\File\PathUtils;
use FileUpload\File\StoredFile;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFileDecorator;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

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
        $accessKey = (string)$this->getConfig('accessKey');
        $hostname = $this->composeHostname((string)$this->getConfig('baseHostName'), (string)$this->getConfig('region'));

        $fileName = PathUtils::fileNameSanitize($fileObject->getClientFilename());

        $url = $this->composeUrl($hostname, $this->getConfig('storageZone'), $this->getConfig('storageZonePath'), $fileName);

        $ch = curl_init();

        if ($ch === false) {
            throw new RuntimeException('Failed to initialise BunnyCDN upload request.');
        }

        $streamUri = $fileObject->getStream()->getMetadata('uri');

        if (!is_string($streamUri) || $streamUri === '') {
            curl_close($ch);
            throw new RuntimeException('Unable to resolve uploaded file stream Uri.');
        }

        $streamResource = fopen($streamUri, 'rb');

        if ($streamResource === false) {
            curl_close($ch);
            throw new RuntimeException('Unable to open uploaded file stream.');
        }

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PUT => true,
            CURLOPT_INFILE => $streamResource,
            CURLOPT_INFILESIZE => $fileObject->getSize(),
            CURLOPT_HTTPHEADER => [
                "AccessKey: {$accessKey}",
                'Content-Type: application/octet-stream',
            ],
        ];

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $curlError = $response === false ? curl_error($ch) : null;

        curl_close($ch);
        fclose($streamResource);

        if ($response === false) {
            throw new RuntimeException(sprintf('Failed to upload file to BunnyCDN: %s', $curlError ?? 'unknown error'));
        }

        $decodedResponse = json_decode($response, true);

        if (!is_array($decodedResponse) || (isset($decodedResponse['HttpCode']) && (int)$decodedResponse['HttpCode'] !== 201)) {
            throw new RuntimeException('Failed to upload file to BunnyCDN.');
        }

        return new UploadedFileDecorator($fileObject, self::STORAGE_TYPE, [
            'storagePath' => $this->composeUrl($this->getConfig('cdnDomain'), $this->getConfig('storageZonePath')),
            'fileName' => $fileName,
        ]);
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
        $segments = array_filter($url, fn ($part) => is_string($part) && $part !== '');

        if ($segments === []) {
            return '';
        }

        $segments = array_map(static fn (string $part): string => trim($part, '/'), $segments);

        $base = array_shift($segments);
        $prefix = preg_match('#^https?://#i', $base) === 1 ? '' : 'https://';

        $path = $segments ? '/' . implode('/', $segments) : '';

        return $prefix . $base . $path;
    }

    private function composeHostname(string $baseHostName, string $region): string
    {
        $trimmedBase = trim($baseHostName);

        if ($trimmedBase === '') {
            throw new RuntimeException('BunnyCDN base host name is not configured.');
        }

        $trimmedRegion = trim($region);

        if ($trimmedRegion === '') {
            return $trimmedBase;
        }

        return $trimmedRegion . '.' . $trimmedBase;
    }
}