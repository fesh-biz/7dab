<?php

namespace App\Api\Google;

use Google\Service\YouTube\VideoListResponse;
use Google_Client;
use Google_Service_YouTube;

class YouTube
{
    protected string $key;
    protected Google_Client $client;
    protected Google_Service_YouTube $service;
    
    public function __construct()
    {
        $this->key = env('YOU_TUBE_KEY');
        $this->client = new Google_Client();
        $this->client->setDeveloperKey($this->key);
        $this->service = new Google_Service_YouTube($this->client);
    }
    
    public function getVideoData(string $videoId): VideoListResponse
    {
        return $this->service->videos->listVideos('snippet', ['id' => $videoId]);
    }
}