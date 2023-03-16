<?php

namespace App\Services\Content;

use App\Api\Google\YouTube;
use App\Models\Content\PostYouTube;
use App\Repositories\Content\PostYouTubeRepository;
use Google\Service\YouTube\Video;

class PostYouTubeService
{
    protected PostYouTubeRepository $repo;
    protected YouTube $api;
    
    public function __construct()
    {
        $this->repo = new PostYouTubeRepository();
        $this->api = new YouTube();
    }
    
    public function getModel(): PostYouTube
    {
        return $this->repo->getModel();
    }
    
    public function create (int $postId, int $order, array $content) {
        $this->getModel()
            ->create([
                'post_id' => $postId,
                'order' => $order,
                'youtube_id' => $content['youtube_id'],
                'title' => $content['title']
            ]);
    }
    
    public function getVideoData(string $videoId): array
    {
        $res = $this->api->getVideoData($videoId)->getItems();
        if (!count($res)) {
            abort(422, trans('errors.video_for_given_youtube_link_was_not_found'));
        }

        $video = $res[0];

        return [
            'youtube_id' => $videoId,
            'thumbnail' => $video->getSnippet()->getThumbnails()->getStandard()->getUrl(),
        ];
    }
}