<?php

namespace App\Models\Content;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, Slugable;

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
