<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content\PostStat
 *
 * @method static \Database\Factories\Content\PostStatFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PostStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostStat query()
 * @mixin \Eloquent
 */
class PostStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'views',
        'positive_votes',
        'negative_votes',
        'comments'
    ];
}
