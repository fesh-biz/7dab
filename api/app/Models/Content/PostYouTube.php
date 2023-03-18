<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content\PostYouTube
 *
 * @property int $id
 * @property int $post_id
 * @property int $order
 * @property string $youtube_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostYouTube whereYoutubeId($value)
 * @mixin \Eloquent
 */
class PostYouTube extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'post_id',
        'order',
        'youtube_id'
    ];
}
