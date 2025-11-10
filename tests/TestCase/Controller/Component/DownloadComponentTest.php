<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\HttpException;
use Cake\TestSuite\TestCase;
use FileUpload\Controller\Component\DownloadComponent;
use FileUpload\Storage\StorageManagerInterface;

/**
 * FileUpload\Controller\Component\DownloadComponent Test Case
 */
class DownloadComponentTest extends TestCase
{
    protected DownloadComponent $Download;

    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Download = new DownloadComponent($registry, [
            'managerClass' => TestStorageManager::class,
        ]);
    }

    public function tearDown(): void
    {
        unset($this->Download);

        parent::tearDown();
    }

    public function testGetFileReturnsStoredFile(): void
    {
        TestStorageManager::reset();

        $storedFile = $this->Download->getFile('image.png');

        $this->assertSame('content-for-image.png', $storedFile->getContent());
        $this->assertSame('text/plain', $storedFile->getMimeType());

        $manager = TestStorageManager::$lastInstance;
        $this->assertNotNull($manager);
        $this->assertSame('image.png', $manager->lastPulledFileName);
    }

    public function testGetFileThrowsWhenManagerClassMissing(): void
    {
        $component = new DownloadComponent(new ComponentRegistry());

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Storage manager class is not configured.');

        $component->getFile('file.txt');
    }

    public function testGetFileThrowsWhenManagerClassDoesNotExist(): void
    {
        $component = new DownloadComponent(new ComponentRegistry(), [
            'managerClass' => '\\NonExistent\\StorageManager',
        ]);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Storage manager class "\\NonExistent\\StorageManager" does not exist.');

        $component->getFile('file.txt');
    }

    public function testGetFileThrowsWhenManagerDoesNotImplementInterface(): void
    {
        $component = new DownloadComponent(new ComponentRegistry(), [
            'managerClass' => TestNotAStorageManager::class,
        ]);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage(sprintf('Storage manager must implement %s.', StorageManagerInterface::class));

        $component->getFile('file.txt');
    }
}
