<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Http\Exception\HttpException;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use FileUpload\Controller\Component\UploadComponent;
use FileUpload\File\UploadedFileDecorator;
use FileUpload\Storage\StorageManagerInterface;
use Laminas\Diactoros\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

/**
 * FileUpload\Controller\Component\UploadComponent Test Case
 */
class UploadComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \FileUpload\Controller\Component\UploadComponent
     */
    protected UploadComponent $Upload;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Upload = new UploadComponent($registry, [
            'managerClass' => TestStorageManager::class,
            'fieldName' => 'upload',
        ]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Upload);

        parent::tearDown();
    }

    public function testGetFileReturnsDecorator(): void
    {
        TestStorageManager::reset();
        $controller = $this->createControllerWithUpload('example.txt', 'plain/text');

        $result = $this->Upload->getFile($controller);

        $this->assertInstanceOf(UploadedFileDecorator::class, $result);
        $this->assertSame('stub', $result->getStorageType());
        $this->assertSame('stub-name.txt', $result->getFileName());

        $manager = TestStorageManager::$lastInstance;
        $this->assertNotNull($manager);
        $this->assertInstanceOf(UploadedFileInterface::class, $manager->lastPutFile);
        $this->assertSame('example.txt', $manager->lastPutFile->getClientFilename());
    }

    public function testGetFileThrowsWhenUploadMissing(): void
    {
        $request = (new ServerRequest())->withData('upload', 'invalid');
        $controller = new Controller($request);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Uploaded file data is missing or invalid.');

        $this->Upload->getFile($controller);
    }

    public function testGetFileThrowsWhenManagerClassMissing(): void
    {
        $component = new UploadComponent(new ComponentRegistry(), [
            'fieldName' => 'upload',
        ]);

        $controller = $this->createControllerWithUpload('file.txt', 'text/plain');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Storage manager class is not configured.');

        $component->getFile($controller);
    }

    public function testGetFileThrowsWhenManagerClassDoesNotExist(): void
    {
        $component = new UploadComponent(new ComponentRegistry(), [
            'fieldName' => 'upload',
            'managerClass' => '\\NonExistent\\StorageManager',
        ]);

        $controller = $this->createControllerWithUpload('file.txt', 'text/plain');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Storage manager class "\\NonExistent\\StorageManager" does not exist.');

        $component->getFile($controller);
    }

    public function testGetFileThrowsWhenManagerDoesNotImplementInterface(): void
    {
        $component = new UploadComponent(new ComponentRegistry(), [
            'fieldName' => 'upload',
            'managerClass' => TestNotAStorageManager::class,
        ]);

        $controller = $this->createControllerWithUpload('file.txt', 'text/plain');

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage(sprintf('Storage manager must implement %s.', StorageManagerInterface::class));

        $component->getFile($controller);
    }

    private function createControllerWithUpload(string $fileName, string $mimeType): Controller
    {
        $stream = fopen('php://temp', 'wb+');

        if ($stream === false) {
            throw new RuntimeException('Unable to initialise temporary stream.');
        }

        fwrite($stream, 'file-content');
        $size = ftell($stream);
        rewind($stream);

        $uploadedFile = new UploadedFile($stream, $size ?: null, UPLOAD_ERR_OK, $fileName, $mimeType);

        $request = (new ServerRequest())->withData('upload', $uploadedFile);

        return new Controller($request);
    }
}
