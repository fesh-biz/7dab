<?php

namespace App\Policies\Content;

use App\Models\Content\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;
    
    public function publish (User $user, Post $post): bool
    {
        return $user->id === $post->user_id && $post->isDraft();
    }
    
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id && $post->isDraft();
    }
    
    public function update (User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->isModerator() ||
            ($post->user_id == $user->id && $post->isDraft());
    }
}
