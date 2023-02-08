<?php

namespace App\Http\Repositories;

use App\Models\Action;

class ActionRepository
{

    private $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function add(array $params): void
    {
        $this->action->fill($params);
        $this->action->save();
    }

    public function edit(int $actionId, array $params): void
    {
        $this->action
            ->findOrFail($actionId)
            ->fill($params)
            ->save();
    }

    public function findById(int $actionId): action
    {
        return $this->action::findOrFail($actionId);
    }

    public function delete($actionId): void
    {
        $this->action::findOrFail($actionId)->delete();
    }

}
