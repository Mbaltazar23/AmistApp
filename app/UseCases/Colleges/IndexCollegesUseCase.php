<?php

namespace App\UseCases\Colleges;

use DB;
use App\Models\College;

class IndexCollegesUseCase
{

    private $college;

    public function __construct(College $college)
    {
        $this->college = $college;
    }

    public function execute(array $params): object
    {
        $response         = new \stdClass();
        $response->pages = 0;
        $response->total = 0;
        $response->rows  =  $this->college::when(array_key_exists('size', $params), function ($query) use ($params) {
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
                return $query->where('colleges.name', 'like', '%' . $params['filterName'] . '%');
            })
            ->select([
                'colleges.id',
                'colleges.dni',
                'colleges.name',
                'colleges.address',
            ])
            ->get();

        if (array_key_exists('size', $params)) {

            $response->total  = $this->college::when(array_key_exists('filterName', $params), function ($query) use ($params) {
                    return $query->where('colleges.name', 'like', '%' . $params['filterName'] . '%');
                })->count();
            $response->pages = ceil($response->total / $params['size']);
        }
        return $response;
    }
}