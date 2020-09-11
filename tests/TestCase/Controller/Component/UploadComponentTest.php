<?php
declare(strict_types=1);

namespace FileUpload\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use FileUpload\Controller\Component\UploadComponent;

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
    protected $Upload;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Upload = new UploadComponent($registry);
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
}
