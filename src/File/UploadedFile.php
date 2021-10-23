<?php
declare(strict_types=1);

namespace FileUpload\File;

class UploadedFile implements StoredFileInterface
{
    protected $fileName;

    protected $path;

    protected $storageType;

    protected $fileType;

    protected $fileContent;

    /**
     * @return mixed
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName File name
     * @return void
     */
    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;

        $pathInfo = pathinfo($fileName);
        $this->fileType = $pathInfo['extension'];
    }

    /**
     * @return mixed
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param mixed $path Path to file without filename
     * @return void
     */
    public function setPath($path): void
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
     * @return mixed
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
