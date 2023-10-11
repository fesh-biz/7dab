<?php

namespace App\Redis\Models;

use App\Plugins\Redis\Model;

/**
 * Class MediaRedis
 * @package App\Redis\Models
 * @property string $mime_type
 * @property int $total_chunks
 * @property array $chunks
 * @property int $failed_attempts
 * @mixin Model
 */
class MediaRedis extends Model
{

}
