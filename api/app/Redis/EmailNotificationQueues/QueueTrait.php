<?php

namespace App\Redis\EmailNotificationQueues;

trait QueueTrait
{
    public function  add(int $id)
    {
        $this->create($id, ['is_sent' => false]);
    }
    
    public function  markAsSent(int $id)
    {
        $this->create($id, ['is_sent' => true]);
    }
    
    public function  getNotificationsForSending(): array
    {
        return $this->search('is_sent', false);
    }
    
    public function  getIdsForSending(): array
    {
        $pendingEntities = $this->search('is_sent', false);
        
        return array_keys($pendingEntities);
    }
    
    public function  deleteSentNotifications()
    {
        $res = $this->search('is_sent', true);
        
        $ids = array_keys($res);
        
        $this->deleteMultiple($ids);
    }
}