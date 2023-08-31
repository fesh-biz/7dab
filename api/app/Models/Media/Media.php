<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Media\Media
 *
 * @method static \Database\Factories\Media\MediaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @mixin \Eloquent
 */
class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $guarded = ['id'];

    public function attachMediable(Model $m): bool
    {
        static::update(['mediable_id' => $m->id, 'mediable_type' => $m::class]);
    }
}
