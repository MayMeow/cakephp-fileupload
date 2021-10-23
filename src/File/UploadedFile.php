<?php

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
    public function getFileName() : string
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
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
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getStorageType() : string
    {
        return $this->storageType;
    }

    /**
     * @param mixed $storageType
     */
    public function setStorageType($storageType): void
    {
        $this->storageType = $storageType;
    }

    /**
     * @return mixed
     */
    public function getFileType() : string
    {
        return $this->fileType;
    }

    public function setFileContent($fileContent)
    {
        $this->fileContent = $fileContent;
    }

    public function getFileContent(): string
    {
        return $this->fileContent;
    }
}
