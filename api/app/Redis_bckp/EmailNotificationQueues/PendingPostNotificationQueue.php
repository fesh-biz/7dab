<?php

namespace App\Redis_bckp\EmailNotificationQueues;

use App\Redis_bckp\Redis;

class PendingPostNotificationQueue extends Redis implements QueueInterface
{
    use QueueTrait;
}
