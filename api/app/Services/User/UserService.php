<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\User\UserData;
use App\Repositories\Content\CommentRepository;
use App\Repositories\Content\PostRepository;
use App\Repositories\Rating\RatingRepository;
use App\Repositories\Rating\RatingVoteRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\UploadedFile;
use Image;
use Storage;

class UserService
{
    protected UserRepository $repo;
    
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function getModel(): User
    {
        return $this->repo->getModel();
    }
    
    public function uploadAvatar(UploadedFile $avatar): bool
    {
        $storageBasePath = storage_path('app/public/user-avatars');
        foreach (['o', 'r'] as $f) {
            $dir = "$storageBasePath/$f";
            if (!is_dir($dir)) {
                mkdir($dir, 755, true);
            }
        }
        
        $user = auth()->user();
        $originalPath = "$storageBasePath/o/$user->login.jpg";
        file_put_contents($originalPath, file_get_contents($avatar));

        $resized = Image::make($avatar)->resize(25, 25);
        $resizedPath = "$storageBasePath/r/$user->login.jpg";
        $resized->save($resizedPath);
        
        $user->has_avatar = true;
        $user->save();
        return true;
    }
    
    public function getStats(int $userId): array
    {
        $ratingRepo = app()->make(RatingRepository::class);
        $res['rating'] = $ratingRepo->getUserRating($userId);
        
        $voteRepo = app()->make(RatingVoteRepository::class);
        $res['votes'] = $voteRepo->getUserVotes($userId);
        
        $commentRepo = app()->make(CommentRepository::class);
        $res['total_comments'] = $commentRepo->getTotalUserComments($userId);
        
        $postRepo = app()->make(PostRepository::class);
        $res['total_posts'] = $postRepo->getTotalUserApprovedPosts($userId);
        
        $user = $this->getModel()->find($userId);
        
        $res['profile'] = [
            'login' => $user->login,
            'with_us_since' => $user->created_at
        ];
        
        return $res;
    }
    
    public function getContentStats(int $userId): array
    {
        $res['posts'] = app()->make(PostRepository::class)
            ->getTotalUserPostsByStatuses($userId, ['approved', 'pending', 'draft']);
        
        $commentRepo = app()->make(CommentRepository::class);
        $res['total_comments'] = $commentRepo->getTotalUserComments($userId);
        
        $res['total_answers'] = $commentRepo->getTotalAnswersOnUserContent($userId);
        
        return $res;
    }
}