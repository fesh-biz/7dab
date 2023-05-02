<?php

namespace App\Redis\EmailNotificationQueues;

interface QueueInterface
{
    public function add(int $id);
    
    public function markAsSent(int $id);
    
    public function getNotificationsForSending(): array;
    
    public function deleteSentNotifications();
}