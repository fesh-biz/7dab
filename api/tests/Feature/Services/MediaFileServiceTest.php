<?php

namespace Tests\Feature\Services;

use App\Services\Media\MediaFileService;
use File;
use Tests\TestCase;

class MediaFileServiceTest extends TestCase
{
    protected MediaFileService $service;

    public function setUp(): void
    {
        parent::setUp();


        $this->service = app()->make(MediaFileService::class);
        $this->service->deleteBaseFolders();
        $this->service->createBaseFolders();
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function delete_base_folders()
    {
        $this->assertTrue(is_dir($this->service->chunksBasePath));
        $this->assertTrue(is_dir($this->service->mergedFilesBasePath));

        $this->service->deleteBaseFolders();
        $this->assertNotTrue(is_dir($this->service->chunksBasePath));
        $this->assertNotTrue(is_dir($this->service->mergedFilesBasePath));

        $this->service->createBaseFolders();
        $this->assertTrue(is_dir($this->service->chunksBasePath));
        $this->assertTrue(is_dir($this->service->mergedFilesBasePath));
    }
}
