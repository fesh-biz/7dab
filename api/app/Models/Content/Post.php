<?php

namespace App\Models\Content;

use App\Models\User;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Content\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $rating
 * @property string $slug
 * @property int $is_approved
 * @property int $total_views
 * @property int $total_comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read User $user
 * @method static \Database\Factories\Content\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTotalComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTotalViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory, Slugable;

    public static string $PENDING = 'pending';
    public static string $REVIEWING = 'reviewing';
    public static string $APPROVED = 'approved';
    public static string $DECLINED = 'declined';
    public static string $EDITING = 'editing';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function postTexts(): HasMany
    {
        return $this->hasMany(PostText::class);
    }

    public function postImages(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
