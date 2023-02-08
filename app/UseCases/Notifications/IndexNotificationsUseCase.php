<?php

namespace App\UseCases\Notifications;

use DB;
use App\Models\Notification;

class IndexNotificationsUseCase
{

    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function execute(array $params): object
    {
        $response         = new \stdClass();
        $response->pages = 0;
        $response->total = 0;
        $response->rows  =  $this->notification::when(array_key_exists('size', $params), function ($query) use ($params) {
                return $query->offset($params['size'] * $params['page']);
            })
            ->when(array_key_exists('sortBy', $params), function ($query) use ($params) {
                return $query->orderBy(
                    $params['sortBy'],
                    $params['sortDesc'] == 'true' ? 'desc' : 'asc'
                );
            })
            ->when(array_key_exists('size', $params), function ($query) use ($params) {
                return $query->limit($params['size']);
            })
            ->when(array_key_exists('filterName', $params), function ($query) use ($params) {
                return $query->where('notifications.message', 'like', '%' . $params['filterName'] . '%');
            })
            ->select([
                'notifications.id',
                'notifications.message',
                'notifications.type',
                'notifications.points',
            ])
            ->get();

        if (array_key_exists('size', $params)) {

            $response->total  = $this->notification::when(array_key_exists('filterName', $params), function ($query) use ($params) {
                    return $query->where('notifications.message', 'like', '%' . $params['filterName'] . '%');
                })->count();
            $response->pages = ceil($response->total / $params['size']);
        }
        return $response;
    }
}