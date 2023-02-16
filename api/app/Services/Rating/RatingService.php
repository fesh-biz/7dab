<?php

namespace App\Services\Rating;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Models\Rating\Rating;
use App\Models\Rating\RatingVote;
use App\Models\User;
use App\Repositories\Rating\RatingRepository;
use App\Repositories\Rating\RatingVoteRepository;
use DB;

class RatingService
{
    protected RatingRepository $repo;
    protected RatingVoteRepository $voteRepo;
    
    public function __construct(RatingRepository $repo, RatingVoteRepository $voteRepo)
    {
        $this->repo = $repo;
        $this->voteRepo = $voteRepo;
    }
    
    public function getModel(): Rating
    {
        return $this->repo->getModel();
    }
    
    public function vote(int $id, string $type, bool $isUpvote): RatingVote
    {
        $userId = auth()->id();
        $model = $this->getRatingableModel($type);
        
        DB::beginTransaction();
        $ratingVote = $this->voteRepo->getModel()
            ->whereRatingableId($id)
            ->whereRatingableType($model)
            ->whereUserId($userId)
            ->first();
        
        $rating = $this->getModel()
            ->firstOrCreate([
                'ratingable_id' => $id,
                'ratingable_type' => $model,
            ]);
        
        $isUpdatingVote = !!$ratingVote;
        $userRating = $this->getUserRatingOfRatingable($id, $model);
        if ($isUpdatingVote) {
            $ratingVote->is_positive = $isUpvote;
            $ratingVote->save();
            
            if ($isUpvote) {
                $rating->negative_votes--;
                $rating->positive_votes++;
                
                $userRating->negative_votes--;
                $userRating->positive_votes++;
            } else {
                $rating->negative_votes++;
                $rating->positive_votes--;
    
                $userRating->negative_votes++;
                $userRating->positive_votes--;
            }
        } else {
            $ratingVote = $this->voteRepo->getModel()
                ->create([
                    'ratingable_id' => $id,
                    'ratingable_type' => $model,
                    'user_id' => $userId,
                    'is_positive' => $isUpvote
                ]);
            
            if ($isUpvote) {
                $rating->positive_votes++;
                $userRating->positive_votes++;
            } else {
                $rating->negative_votes++;
                $userRating->negative_votes++;
            }
        }
        $rating->save();
        $userRating->save();
    
        DB::commit();
        
        return $ratingVote;
    }
    
    public function getUserRatingOfRatingable(int $id, string $modelName): Rating
    {
        $ratingable = $modelName::findOrFail($id);
        
        return Rating::firstOrCreate([
            'ratingable_id' => $ratingable->user_id,
            'ratingable_type' => User::class
        ]);
    }
    
    private function getRatingableModel(string $type): ?string
    {
        $types = [
            'post' => Post::class,
            'comment' => Comment::class
        ];
        
        return $types[$type] ?? null;
    }
}