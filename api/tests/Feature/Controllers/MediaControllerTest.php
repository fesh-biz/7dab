<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    // Upload Method if chunk is last it removes media from redis
    // Upload Method if chunk is last it removes media_id from redis user
    // Upload Method if chunk is last it removes redis user if its media_ids is empty
    // Upload Method if chunk is last and merged filesize !== $media->original_size remove all
    // Upload Method if chunk is last it uploads file to DigitalOcean Space
    // Upload Method if chunk is last it updates media with DO Space url
}
