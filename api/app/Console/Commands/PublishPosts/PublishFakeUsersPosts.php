<?php

namespace App\Console\Commands\PublishPosts;

use App\Models\Content\Post;
use Illuminate\Console\Command;

class PublishFakeUsersPosts extends Command
{
    protected $signature = 'publish-posts:publish-fake-users-posts';
    
    protected $description = 'Command description';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle(): void
    {
        $firstPostFromFakeUser = Post::where('user_id', '>=', 11)
            ->where('user_id', '<=', 28)
            ->where('status', '!=', 'approved')
            ->orderBy('id')
            ->first();
        
        if (!$firstPostFromFakeUser) return;

        $firstPostFromFakeUser->created_at = now()->subMinutes(mt_rand(5, 80));
        $firstPostFromFakeUser->status = 'approved';
        $firstPostFromFakeUser->save();
    }
}
