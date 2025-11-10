<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\File;

use FileUpload\File\UploadedFileDecorator;
use Laminas\Diactoros\UploadedFile;
use PHPUnit\Framework\TestCase;

class UploadedFileDecoratorTest extends TestCase
{
    public function testGetReturnsOptionsWithDefault(): void
    {
        $decorator = new UploadedFileDecorator($this->createUploadedFile('example.txt'), 'local', [
            'custom' => 'value',
            'fileName' => 'stored.txt',
        ]);

        $this->assertSame('value', $decorator->get('custom'));
        $this->assertSame('fallback', $decorator->get('missing', 'fallback'));
        $this->assertSame('stored.txt', $decorator->getFileName());
    }

    public function testGetFileNameFallsBackToOriginal(): void
    {
        $decorator = new UploadedFileDecorator($this->createUploadedFile('original.txt'), 'local');

        $this->assertSame('original.txt', $decorator->getFileName());
    }

    private function createUploadedFile(string $fileName): UploadedFile
    {
        $stream = fopen('php://temp', 'wb+');

        if ($stream === false) {
            $this->fail('Unable to allocate temporary stream.');
        }

        fwrite($stream, 'sample');
        $size = ftell($stream);
        rewind($stream);

        return new UploadedFile($stream, $size ?: null, UPLOAD_ERR_OK, $fileName, 'text/plain');
    }
}
