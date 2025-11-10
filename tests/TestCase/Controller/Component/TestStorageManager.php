<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\Controller\Component;

use FileUpload\File\StoredFileInterface;
use FileUpload\File\UploadedFileDecorator;
use FileUpload\Storage\StorageManager;
use Psr\Http\Message\UploadedFileInterface;

final class TestStorageManager extends StorageManager
{
    public static ?self $lastInstance = null;

    public ?UploadedFileInterface $lastPutFile = null;

    public ?string $lastPulledFileName = null;

    public function __construct(array $configurations = [])
    {
        parent::__construct($configurations);
        self::$lastInstance = $this;
    }

    public static function reset(): void
    {
        self::$lastInstance = null;
    }

    public function put(UploadedFileInterface $fileObject): UploadedFileDecorator
    {
        $this->lastPutFile = $fileObject;

        return new UploadedFileDecorator($fileObject, 'stub', [
            'fileName' => 'stub-name.txt',
        ]);
    }

    public function pull(string $fileName): StoredFileInterface
    {
        $this->lastPulledFileName = $fileName;

        return new TestStoredFile('content-for-' . $fileName, 'text/plain');
    }
}

final class TestStoredFile implements StoredFileInterface
{
    public function __construct(
        private string $content,
        private string $mimeType
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}

final class TestNotAStorageManager
{
    public function __construct(array $configurations = [])
    {
    }
}
