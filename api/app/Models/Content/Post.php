<?php

namespace App\Models\Content;

use App\Models\User;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Content\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $rating
 * @property string $slug
 * @property string $status
 * @property int $total_views
 * @property int $total_comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\PostImage[] $postImages
 * @property-read int|null $post_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\PostText[] $postTexts
 * @property-read int|null $post_texts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read User $user
 * @method static \Database\Factories\Content\PostFactory factory(...$parameters)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereRating($value)
 * @method static Builder|Post whereSlug($value)
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereTotalComments($value)
 * @method static Builder|Post whereTotalViews($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUserId($value)
 * @method static Builder|Post withTagsAuthorContent()
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;

    public static string $PENDING = 'pending';
    public static string $REVIEWING = 'reviewing';
    public static string $APPROVED = 'approved';
    public static string $DECLINED = 'declined';
    public static string $EDITING = 'editing';

    protected $fillable = [
        'title', 'user_id', 'status'
    ];
    
    public static function boot()
    {
        parent::boot();
        
        self::creating(function($m){
            $m->slug = \Str::slug($m->title) . '-' . time();
        });
        
        self::updating(function($m){
            $m->slug = \Str::slug($m->title) . '-' . time();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->where('status', 'approved');
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

    public function postStat(): HasOne
    {
        return $this->hasOne(PostStat::class);
    }

    public function scopeWithTagsAuthorContent(Builder $q): Builder
    {
        return $q->with([
            'tags:id,title',
            'user:id,login',
            'postImages',
            'postTexts',
            'postStat'
        ]);
    }
}
