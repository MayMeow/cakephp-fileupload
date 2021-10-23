<?php
declare(strict_types=1);

namespace FileUpload\File;

interface StoredFileInterface
{
    /**
     * Returns extension of file
     *
     * @return string
     */
    public function getFileType(): string;

    /**
     * Returns storage type
     *
     * @return string
     */
    public function getStorageType(): string;

    /**
     * Returns path to file in storage
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Returns file name
     *
     * @return string
     */
    public function getFileName(): string;

    /**
     * Returns content of file
     *
     * @return string
     */
    public function getFileContent(): string;
}
