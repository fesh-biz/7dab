<?php

namespace Tests\Feature\Services;

use App\Services\Media\MediaFileService;
use File;
use Illuminate\Http\UploadedFile;
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
    public function merge_file_chunks_creates_merged_file()
    {
        $contents = [
            'content1',
            'content2',
            'content3',
            'content4',
            'content5',
        ];

        $mediaId = 325;
        $mime = 'image/gif';
        $chunks = $this->createFakeChunks($mediaId, $contents, 'gif');


        $expectedFileContent = '';
        foreach ($contents as $c) {
            $expectedFileContent .= $c;
        }

        $mergedFile = $this->service->mergeFileChunks($mediaId, $mime, $chunks);
        $mergedFileContent = file_get_contents($mergedFile);
        $this->assertEquals($expectedFileContent, $mergedFileContent);
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function merge_file_chunks_has_exception_if_chunk_filename_is_missing()
    {

        $wrongChunk = new \stdClass();
        $mediaId = 2;
        $wrongChunk->filename = $this->service->getFilePath('notAFile.jpg', $mediaId);
        $mime = 'image/jpeg';

        $expectedMessage = 'Chunk is missing: ' . $wrongChunk->filename;

        $this->expectExceptionMessage($expectedMessage);
        $this->service->mergeFileChunks($mediaId, $mime, [$wrongChunk]);
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function check_file_has_exploits()
    {
        $this->expectExceptionMessage('File has exploit: <?php');
        $this->service->checkFileHasExploits('safdsafdsa<?phpaa.gasdg');

        $this->expectExceptionMessage('File has exploit: phar');
        $this->service->checkFileHasExploits('safdsafdsapharaa.gasdg');
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function store_chunk()
    {
        $file = UploadedFile::fake()->create('test.jpeg', 214);
        $mediaId = 24;
        $filename = $this->service->storeChunk($mediaId, $file);

        $this->assertTrue(is_file($filename));
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function get_file_path()
    {
        $mediaId = 42;
        $filename = 'test-file-name.jpg';

        $expectedChunkFilePath = $this->service->chunksBasePath . "/media-$mediaId/$filename";
        $chunkFilePath = $this->service->getFilePath($filename, $mediaId);
        $this->assertEquals($expectedChunkFilePath, $chunkFilePath);

        $expectedMergedFilePath = $this->service->mergedFilesBasePath . "/media-$mediaId/$filename";
        $mergedFilePath = $this->service->getFilePath($filename, $mediaId, false);
        $this->assertEquals($expectedMergedFilePath, $mergedFilePath);
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function create_media_merged_files_folder()
    {
        $id = 1;
        $dir = $this->service->createMediaMergedFilesFolder($id);

        $this->assertTrue(is_dir($dir));
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function delete_media_merged_files_folder()
    {
        $id = 1;
        $dir = $this->service->createMediaMergedFilesFolder($id);
        $this->service->deleteMediaMergedFilesFolder($id);

        $this->assertNotTrue(is_dir($dir));
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function create_media_chunks_folder()
    {
        $dir = $this->service->createMediaChunksFolder(1);
        $this->assertTrue(is_dir($dir));
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function delete_media_chunks_folder()
    {
        $id = 1;
        $dir = $this->service->createMediaChunksFolder($id);
        $this->service->deleteMediaChunksFolder($id);
        $this->assertNotTrue(is_dir($dir));
    }

    /**
     * @test
     * @group MediaFileService
     */
    public function create_base_folders()
    {
        $this->service->deleteBaseFolders();
        $this->assertNotTrue(is_dir($this->service->chunksBasePath));
        $this->assertNotTrue(is_dir($this->service->mergedFilesBasePath));

        $this->service->createBaseFolders();
        $this->assertTrue(is_dir($this->service->chunksBasePath));
        $this->assertTrue(is_dir($this->service->mergedFilesBasePath));
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
    }

    private function createFakeChunks(int $mediaId, array $chunksContent, string $ext = 'jpeg'): array
    {
        $chunks = [];
        foreach ($chunksContent as $id => $content) {
            $file = UploadedFile::fake()->createWithContent("file-$id.$ext", $content);

            $chunk = new \stdClass();
            $chunk->filename = $this->service->storeChunk($mediaId, $file);

            $chunks[] = $chunk;
        }

        return $chunks;
    }
}
