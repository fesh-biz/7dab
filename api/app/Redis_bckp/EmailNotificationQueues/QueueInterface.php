<?php

namespace App\Redis_bckp\EmailNotificationQueues;

interface QueueInterface
{
    public function add(int $id);

    public function markAsSent(int $id);

    public function  getIdsForSending(): array;

    public function getNotificationsForSending(): array;

    public function deleteSentNotifications();
}
