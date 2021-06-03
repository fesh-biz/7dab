<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;

    public function comments(): MorphMany
    {
        return $this->morphMany(self::class, 'commentable')
            ->with('comments');
    }
}
