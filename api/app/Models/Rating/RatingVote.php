<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rating\RatingVote
 *
 * @property int $id
 * @property int $user_id
 * @property int $ratingable_id
 * @property string $ratingable_type
 * @property int $is_positive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Rating\RatingVoteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote query()
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereIsPositive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereRatingableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereRatingableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RatingVote whereUserId($value)
 * @mixin \Eloquent
 */
class RatingVote extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'ratingable_id',
        'ratingable_type',
        'is_positive'
    ];
    
    protected $hidden = [
        'ratingable_type'
    ];
    
    protected $appends = [
        'ratingable_type_name'
    ];
    
    public function getRatingableTypeNameAttribute(): string
    {
        $value = $this->attributes['ratingable_type'];
        $res = explode('\\', $value);
        $res = end($res);
        return strtolower($res) . 's';
    }
}
