<?php

namespace Tests\Feature\Controllers;

use App\Data\User\UserRedisData;
use App\Models\Media\Media;
use App\Models\User;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Repositories\UserRedisRepository;
use App\Services\Media\MediaFileService;
use App\Services\Media\MediaService;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    protected MediaService $mediaService;
    protected UserRedisRepository $userRedisRepo;
    protected MediaRedisRepository $mediaRedisRepo;
    protected MediaFileService $fileService;


    public function setUp(): void
    {
        parent::setUp();

        $this->fileService = app()->make(MediaFileService::class);
        $this->fileService->deleteBaseFolders();
        $this->fileService->createBaseFolders();

        $this->artisan('db:seed');

        $this->mediaService = app()->make(MediaService::class);
        $this->userRedisRepo = app()->make(UserRedisRepository::class);
        $this->mediaRedisRepo = app()->make(MediaRedisRepository::class);

        $this->userRedisRepo->deleteAll();
        $this->mediaRedisRepo->deleteAll();
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_must_be_authorized()
    {
        $res = $this->postJson(route('media.checkFile'));

        $this->assertTrue(
            $res->getStatusCode() === 401,
            'Request must be authenticated'
        );
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_throw_missing_file_exception()
    {
        ['res' => $res] = $this->sendCheckFileRequestAsUser();

        $this->assertTrue(
            $res->message === 'Chunk File is missing',
            'Missed empty file check'
        );
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_throw_exception_about_too_large_chunk()
    {
        $postData = [
            'file_chunk' => UploadedFile::fake()->create('test.jpg', 101),
            'size' => 23
        ];

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $expectedMessage = 'The given file chunk is too large';
        $this->assertTrue(
            $res->message === $expectedMessage,
            "Expected message '$expectedMessage' not found in response"
        );
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_throw_exception_about_wrong_mime_type()
    {
        $fileWithWrongFirstBytes = UploadedFile::fake()->create('test.jpg', 100);
        $postData = [
            'file_chunk' => $fileWithWrongFirstBytes,
            'size' => 23
        ];

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $expectedMessage = trans('validation.wrong_file_type');
        $this->assertTrue(
            $res->message === $expectedMessage,
            "Expected message '$expectedMessage' not found in response"
        );
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allow_specific_mime_types()
    {
        $basePath = base_path('tests/files');
        $files = scandir($basePath);
        $filesToCheck = [];
        foreach ($files as $filename) {
            $filePath = $basePath . "/$filename";
            if (is_file($filePath)) {
                $filesToCheck[] = $filePath;
            }
        }

        foreach ($filesToCheck as $filename) {
            $file = fopen($filename, 'rb');
            $content = fread($file, 100);
            $extension = pathinfo($filename)['extension'];
            $postData = [
                'file_chunk' => UploadedFile::fake()->createWithContent('test.' . $extension, $content),
                'size' => 2332
            ];

            ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

            $this->assertTrue($res->status === 'success');
        }
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_passes_jpg_less_or_equal_upload_max_filesize()
    {
        $postData = $this->getCheckFilePostData('jpg');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_jpg()
    {
        $postData = $this->getCheckFilePostData('jpg', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allows_png_less_or_equal_upload_max_filesize()
    {
        $postData = $this->getCheckFilePostData('png');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_png()
    {
        $postData = $this->getCheckFilePostData('png', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allows_webp_less_or_equal_upload_max_filesize()
    {
        $postData = $this->getCheckFilePostData('webp');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_webp()
    {
        $postData = $this->getCheckFilePostData('webp', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allows_gif_less_or_equal_upload_max_filesize_multiplied_10()
    {
        $postData = $this->getCheckFilePostData('gif');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_gif()
    {
        $postData = $this->getCheckFilePostData('gif', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allows_mp4_less_or_equal_upload_max_filesize_multiplied_20()
    {
        $postData = $this->getCheckFilePostData('mp4');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_mp4()
    {
        $postData = $this->getCheckFilePostData('mp4', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_allows_webm_less_or_equal_upload_max_filesize_multiplied_20()
    {
        $postData = $this->getCheckFilePostData('webm');

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->status === 'success');
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_webm()
    {
        $postData = $this->getCheckFilePostData('webm', true);

        ['res' => $res] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertTrue($res->errors->file_chunk[0] === trans('validation.file_is_too_large'));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_creates_user_in_redis()
    {
        $this->assertTrue($this->userRedisRepo->count() === 0);

        $postData = $this->getCheckFilePostData('webm');

        ['user' => $u] = $this->sendCheckFileRequestAsUser($postData);
        $userRedis = $this->userRedisRepo->find($u->id);

        $this->assertNotNull($userRedis);
        $this->assertEquals($userRedis->id, $u->id);
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_doesnt_override_user_in_redis_and_adds_media_id_to_its()
    {
        $user = User::first();
        $media = Media::factory()->create();
        $userRedisData = new UserRedisData($user->id, [$media->id]);
        $this->userRedisRepo->create($userRedisData);

        $postData = $this->getCheckFilePostData('webm');

        ['user' => $u, 'res' => $r] = $this->sendCheckFileRequestAsUser($postData, $user);
        $userRedis = $this->userRedisRepo->find($u->id);

        $this->assertTrue(in_array($media->id, $userRedis->media_ids));
        $this->assertTrue(in_array($r->media_id, $userRedis->media_ids));
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_creates_media_in_redis()
    {
        $postData = $this->getCheckFilePostData('jpg');

        ['user' => $u, 'res' => $r] = $this->sendCheckFileRequestAsUser($postData);

        $mediaDBData = [
            'user_id' => $u->id,
            'original_filename' => $postData['file_chunk']->getClientOriginalName(),
            'mime_type' => 'image/jpeg',
            'original_size' => $postData['size']
        ];

        $this->assertDatabaseHas('media', $mediaDBData);
    }

    /**
     * @test
     * @group MediaController
     */
    public function check_file_creates_media_in_database()
    {
        $postData = $this->getCheckFilePostData('jpg');

        ['user' => $u, 'res' => $r] = $this->sendCheckFileRequestAsUser($postData);

        $this->assertNotNull($this->mediaRedisRepo->find($r->media_id));
    }

    /**
     * @tes
     * @group MediaController
     */
    public function store_chunk_throws_exception_about_max_sum_of_all_chunks_has_been_reached()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function store_chunk_()
    {

    }

    // Upload Method if chunk is last it removes media from redis
    // Upload Method if chunk is last it removes media_id from redis user
    // Upload Method if chunk is last it removes redis user if its media_ids is empty
    // Upload Method if chunk is last and merged filesize !== $media->original_size remove all
    // Upload Method if chunk is last it uploads file to DigitalOcean Space
    // Upload Method if chunk is last it updates media with DO Space url

    private function sendCheckFileRequestAsUser(array $postData = [], User $user = null): array
    {
        $user = $user ?: User::first();
        Passport::actingAs($user);

        $res = $this->actingAs($user)
            ->postJson(route('media.checkFile'), $postData)
            ->getContent();

        return [
            'res' => json_decode($res),
            'user' => $user
        ];
    }

    private function getMediaFilePath(string $extension): string
    {
        $basePath = base_path('tests/files');
        $files = scandir($basePath);

        foreach ($files as $filename) {
            $filePath = $basePath . "/$filename";
            if (is_file($filePath) && pathinfo($filePath)['extension'] === $extension) {
                return $filePath;
            }
        }

        throw new \Exception('Wrong extension: ' . $extension);
    }

    private function getFirst100Bytes(string $filename): string
    {
        $res = fopen($filename, 'rb');
        return fread($res, 100);
    }

    private function getPostData(UploadedFile $file, int $size): array
    {
        return [
            'file_chunk' => $file,
            'size' => $size
        ];
    }

    private function getCheckFilePostData(string $extension, bool $isNeedOverload = false): array
    {
        $filename = $this->getMediaFilePath($extension);
        $content = $this->getFirst100Bytes($filename);
        $maxFileSizeInKb = getUploadMaxFilesize() * 1024;
        $maxFileSizeInMb = $maxFileSizeInKb * 1024;
        $maxFileSizeInMb = match ($extension) {
            'jpg', 'png', 'webp' => $maxFileSizeInMb,
            'gif' => $maxFileSizeInMb * 10,
            'mp4', 'webm' => $maxFileSizeInMb * 20,
            default => throw new \Exception("Wrong extension: $extension"),
        };

        return $this->getPostData(
            UploadedFile::fake()->createWithContent('test.' . $extension, $content),
            $isNeedOverload ? $maxFileSizeInMb + 1 : $maxFileSizeInMb
        );
    }
}
