<?php
declare(strict_types=1);

namespace FileUpload\Storage;

use Aws\S3\S3Client;
use Cake\Core\Configure;
use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

class S3StorageManager implements StorageManagerInterface
{
    /**
     * @var \FileUpload\Storage\StorageConfigInterface
     */
    protected $configuration;

    /**
     * @var \Aws\S3\S3Client $awsClient
     */
    protected $awsClient;

    public function __construct(StorageConfigInterface $config)
    {
        $this->configuration = $config;
        $this->awsClient = $this->getAwsClient();
    }

    public function put(UploadedFileInterface $fileObject): StoredFileInterface
    {
        $this->awsClient->putObject([
            'Bucket' => $this->configuration->getConfig('storagePath'),
            'Key' => $fileObject->getClientFilename(),
            'Body' => $fileObject->getStream(),
        ]);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileObject->getClientFilename());
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));

        return $uploadedFile;
    }

    protected function getAwsClient(): S3Client
    {
        return new S3Client(Configure::read('S3'));
    }

    public function pull(string $fileName): StoredFileInterface
    {
        $downloadedFile = $this->awsClient->getObject([
            'Bucket' => $this->configuration->getConfig('storagePath'),
            'Key' => $fileName,
            'SaveAs' => $fileName . '_local',
        ]);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setFileName($fileName);
        $uploadedFile->setPath($this->configuration->getConfig('storagePath'));
        $uploadedFile->setStorageType($this->configuration->getConfig('storage_type'));
        $uploadedFile->setFileContent($downloadedFile['Body']);

        return $uploadedFile;
    }
}
