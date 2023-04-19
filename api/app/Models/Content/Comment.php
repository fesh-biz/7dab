<?php

namespace App\Models\Content;

use App\Models\Rating\Rating;
use App\Models\Rating\RatingVote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Content\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @method static \Database\Factories\Content\CommentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $answers
 * @property-read int|null $answers_count
 * @property-read User $user
 * @property-read string $commentable_type_name
 * @property-read RatingVote|null $myVote
 * @property-read Rating|null $rating
 */
class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
        'post_id'
    ];
    
    protected $hidden = [
        'commentable_type'
    ];
    
    protected $appends = [
        'commentable_type_name'
    ];
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function answers(): HasMany
    {
        $query = $this->hasMany(self::class, 'commentable_id', 'id')
            ->where('commentable_type', self::class)
            ->with('answers')
            ->with('rating')
            ->with('user');
    
        if (auth('api')->user()) {
            $query->with('myVote');
        }
        
        return $query;
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getCommentableTypeNameAttribute(): string
    {
        $value = $this->attributes['commentable_type'];
        $res = explode('\\', $value);
        $res = end($res);
        return strtolower($res) . 's';
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
}
