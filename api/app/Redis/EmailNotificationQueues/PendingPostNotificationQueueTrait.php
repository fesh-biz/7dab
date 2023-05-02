<?php

namespace App\Redis\EmailNotificationQueues;

use App\Redis\Redis;

class PendingPostNotificationQueueTrait extends Redis implements QueueInterface
{
    use QueueTrait;
}