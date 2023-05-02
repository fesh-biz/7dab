<?php

namespace App\Console\Commands\Notification;

use App\Models\Content\Post;
use App\Redis\EmailNotificationQueues\PendingPostNotificationQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckPendingPosts extends Command
{
    protected $signature = 'notification:check-pending-posts';
    
    protected $description = 'Check for pending posts and send email to admin';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $postsQueue = app()->make(PendingPostNotificationQueue::class);
        
        $postsIds = $postsQueue->getIdsForSending();
        $postsQueue->deleteAll();

        if (count($postsIds) > 0) {
            $pendingPosts = Post::whereIn('id', $postsIds)->get();
            Mail::send(
                'emails.notification.pending-posts',
                compact('pendingPosts'),
                function ($message) {
                    $message->to('feshbiz@gmail.com')
                        ->from('noreply@terevenky.com', 'Теревеньки')
                        ->subject('Пости очікують перевірки');
                }
            );
        }
    }
}
