<?php

namespace App\Http\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function add(array $params): void
    {
        $this->notification->fill($params);
        $this->notification->save();
    }

    public function edit(int $notificationId, array $params): void
    {
        $this->notification
            ->findOrFail($notificationId)
            ->fill($params)
            ->save();
    }

    public function findById(int $notificationId): notification
    {
        return $this->notification::findOrFail($notificationId);
    }

    public function delete($notificationId): void
    {
        $this->notification::findOrFail($notificationId)->delete();
    }

}
