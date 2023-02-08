<?php

namespace App\Http\Repositories;

use App\Models\College;

class CollegeRepository
{
    private $college;

    public function __construct(College $college)
    {
        $this->college = $college;
    }

    public function add(array $params): void
    {
        $this->college->fill($params);
        $this->college->save();
    }

    public function edit(int $collegeId, array $params): void
    {
        $this->college
            ->findOrFail($collegeId)
            ->fill($params)
            ->save();
    }

    public function findById(int $collegeId): college
    {
        return $this->college::findOrFail($collegeId);
    }

    public function delete($collegeId): void
    {
        $this->college::findOrFail($collegeId)->delete();
    }

}
