<?php

namespace App\Redis\Models;

use App\Plugins\Redis\Model;

/**
 * Class MediaRedis
 * @package App\Redis\Models
 * @property int $id
 * @property string $mime_type
 * @property array $chunks
 * @property int $failed_attempts
 * @property string $created_at
 */
class MediaRedis extends Model
{

}
