<?php

namespace FileUpload\Storage;

interface StorageConfigInterface
{
    public function getConfig(?string $key = null, $default = null);
}
