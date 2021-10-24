<?php
declare(strict_types=1);

namespace FileUpload\File;

class UploadedFile implements StoredFileInterface
{
    /**
     * @var string $fileName
     */
    protected $fileName;

    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var string $storageType
     */
    protected $storageType;

    /**
     * @var string $fileType
     */
    protected $fileType;

    /**
     * @var string $fileContent
     */
    protected $fileContent;

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName File name
     * @return void
     */
    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;

        /** @var string[] $pathInfo */
        $pathInfo = pathinfo($fileName);
        $this->fileType = $pathInfo['extension'];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path Path to file without filename
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getStorageType(): string
    {
        return $this->storageType;
    }

    /**
     * @param string $storageType Storage type
     * @return void
     */
    public function setStorageType(string $storageType): void
    {
        $this->storageType = $storageType;
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->fileType;
    }

    /**
     * @param string $fileContent Content of file
     * @return void
     */
    public function setFileContent(string $fileContent)
    {
        $this->fileContent = $fileContent;
    }

    /**
     * @return string
     */
    public function getFileContent(): string
    {
        return $this->fileContent;
    }
}
