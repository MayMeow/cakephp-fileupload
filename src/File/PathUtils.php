<?php
declare(strict_types=1);

namespace FileUpload\File;

use Cake\Utility\Text;

class PathUtils
{
    public static function fileNameSanitize(string $filename): string
    {
        // get extension even if filename has multiple dots
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = Text::slug($filename);

        if ($ext === '') {
            return $filename;
        }

        return $filename . '.' . $ext;
    }
}