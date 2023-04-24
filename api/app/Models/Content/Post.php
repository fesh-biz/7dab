<?php

namespace App\Models\Content;

use App\Models\Rating\Rating;
use App\Models\Rating\RatingVote;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Content\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $status
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
 * @method static Builder|Post whereStatus($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUserId($value)
 * @method static Builder|Post withTagsAuthorContent()
 * @mixin \Eloquent
 * @property int $views
 * @property-read RatingVote|null $myVote
 * @property-read Rating|null $rating
 * @method static Builder|Post whereComments($value)
 * @method static Builder|Post whereViews($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\PostYouTube[] $postYouTubes
 * @property-read int|null $post_you_tubes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content\Tag[] $previewTags
 * @property-read int|null $preview_tags_count
 * @method static Builder|Post withPreviewRelations()
 */
class Post extends Model
{
    use HasFactory;
    
    public static string $DRAFT = 'draft';
    public static string $PENDING = 'pending';
    public static string $REVIEWING = 'reviewing';
    public static string $APPROVED = 'approved';
    public static string $DECLINED = 'declined';
    public static string $EDITING = 'editing';
    
    protected $fillable = [
        'title', 'user_id', 'status'
    ];
    
    public function isDraft(): bool
    {
        return $this->status === self::$DRAFT;
    }
    
    public function rating(): MorphOne
    {
        return $this->morphOne(Rating::class, 'ratingable');
    }
    
    public function myVote(): MorphOne
    {
        return $this->morphOne(RatingVote::class, 'ratingable', 'ratingable_type', 'ratingable_id')
            ->where('user_id', auth('api')->id());
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
    
    public function previewTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->where('status', '!=', 'rejected');
    }
    
    public function postTexts(): HasMany
    {
        return $this->hasMany(PostText::class);
    }
    
    public function postYouTubes(): HasMany
    {
        return $this->hasMany(PostYouTube::class);
    }
    
    public function postImages(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }
    
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function scopeWithPreviewRelations(Builder $q): Builder
    {
        return $q->with([
            'previewTags:id,title',
            'user:id,login,avatar',
            'postImages',
            'postTexts',
            'postYouTubes'
        ]);
    }
    
    public function scopeWithTagsAuthorContent(Builder $q): Builder
    {
        $query = $q->with([
            'tags:id,title',
            'user:id,login,avatar',
            'postImages',
            'rating',
            'postTexts',
            'postYouTubes'
        ]);
    
        if (auth('api')->user()) {
            $query->with('myVote');
        }
        
        return $query;
    }
}
