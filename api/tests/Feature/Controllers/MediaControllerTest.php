<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
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
     * @tes
     * @group MediaController
     */
    public function check_file_allows_image_less_or_equal_upload_max_filesize()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_image()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_allows_gif_less_or_equal_upload_max_filesize_multiplied_10()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_gif()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_allows_video_less_or_equal_upload_max_filesize_multiplied_20()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_sends_error_about_to_large_video()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_creates_user_in_redis()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_creates_media_in_redis()
    {

    }

    /**
     * @tes
     * @group MediaController
     */
    public function check_file_creates_media_in_database()
    {

    }


    // Upload Method if chunk is last it removes media from redis
    // Upload Method if chunk is last it removes media_id from redis user
    // Upload Method if chunk is last it removes redis user if its media_ids is empty
    // Upload Method if chunk is last and merged filesize !== $media->original_size remove all
    // Upload Method if chunk is last it uploads file to DigitalOcean Space
    // Upload Method if chunk is last it updates media with DO Space url

    private function sendCheckFileRequestAsUser(array $postData = []): array
    {
        $user = User::first();
        Passport::actingAs($user);

        $res = $this->actingAs($user)
            ->postJson(route('media.checkFile'), $postData)
            ->getContent();
        return [
            'res' => json_decode($res),
            'user' => $user
        ];
    }
}
