<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\File;

use FileUpload\File\PathUtils;
use PHPUnit\Framework\TestCase;

class PathUtilsTest extends TestCase
{
    public function testFileNameSanitizePreservesExtension(): void
    {
        $this->assertSame('example-file.txt', PathUtils::fileNameSanitize('Example File.TXT'));
    }

    public function testFileNameSanitizeHandlesMultipleDots(): void
    {
        $this->assertSame('archive.tar.gz', PathUtils::fileNameSanitize('Archive.tar.gz'));
    }

    public function testFileNameSanitizeHandlesMissingExtension(): void
    {
        $this->assertSame('filename', PathUtils::fileNameSanitize('FileName'));
    }
}
