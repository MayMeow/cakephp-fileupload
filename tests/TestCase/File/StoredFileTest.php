<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\File;

use FileUpload\Exceptions\FileContentException;
use FileUpload\File\StoredFile;
use PHPUnit\Framework\TestCase;

class StoredFileTest extends TestCase
{
    public function testGetContentCachesResult(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'fileupload-test-');

        if ($path === false) {
            $this->fail('Unable to create temporary file.');
        }

        file_put_contents($path, 'stored content');

        $storedFile = new StoredFile($path);

        $firstRead = $storedFile->getContent();
        unlink($path);
        $secondRead = $storedFile->getContent();

        $this->assertSame('stored content', $firstRead);
        $this->assertSame($firstRead, $secondRead);
        $this->assertNotSame('', $storedFile->getMimeType());
    }

    public function testGetContentThrowsWhenFileMissing(): void
    {
        $storedFile = new StoredFile('missing-file-' . uniqid('', true));

        $this->expectException(FileContentException::class);
        $storedFile->getContent();
    }
}
