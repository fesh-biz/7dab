<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content\PostImage
 *
 * @property int $id
 * @property int $post_id
 * @property int $order
 * @property string $original_name
 * @property string|null $title
 * @property string|null $recognized_text
 * @property string $filename
 * @property int $width
 * @property int $height
 * @property int $size_kb
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $type
 * @method static \Database\Factories\Content\PostImageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereRecognizedText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereSizeKb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostImage whereWidth($value)
 * @mixin \Eloquent
 */
class PostImage extends Model
{
    use HasFactory;
}
