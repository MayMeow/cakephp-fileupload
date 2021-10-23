<?php

namespace FileUpload\File;

interface StoredFileInterface
{
    public function getFileType() : string;

    public function getStorageType() : string;

    public function getPath() : string;

    public function getFileName() : string;
}
