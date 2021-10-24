<?php
declare(strict_types=1);

namespace Fileupload\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use Fileupload\Controller\Component\DownloadComponent;

/**
 * Fileupload\Controller\Component\DownloadComponent Test Case
 */
class DownloadComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Fileupload\Controller\Component\DownloadComponent
     */
    protected $Download;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Download = new DownloadComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Download);

        parent::tearDown();
    }
}
