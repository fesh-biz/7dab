<?php

namespace App\Redis\EmailNotificationQueues;

use App\Redis\Redis;

class PendingPostNotificationQueue extends Redis implements QueueInterface
{
    use QueueTrait;
}