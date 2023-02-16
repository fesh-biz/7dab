<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rating\Rating
 *
 * @property int $id
 * @property int $ratingable_id
 * @property string $ratingable_type
 * @property int $positive_votes
 * @property int $negative_votes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Rating\RatingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereNegativeVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating wherePositiveVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereRatingableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereRatingableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rating extends Model
{
    use HasFactory;
    
    protected $hidden = [
        'ratingable_id',
        'ratingable_type',
        'positive_votes',
        'negative_votes'
    ];
}
