<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content\PostText
 *
 * @property int $id
 * @property int $post_id
 * @property int $order
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Content\PostTextFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostText query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostText whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostText whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostText extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'order',
        'body'
    ];
}
