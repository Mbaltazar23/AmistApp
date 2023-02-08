<?php

namespace App\UseCases\Categories;

use DB;
use App\Models\Category;

class IndexCategoriesUseCase
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function execute(array $params): object
    {
        $response         = new \stdClass();
        $response->pages = 0;
        $response->total = 0;
        $response->rows  =  $this->category::when(array_key_exists('size', $params), function ($query) use ($params) {
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
                return $query->where('categories.name', 'like', '%' . $params['filterName'] . '%');
            })
            ->select([
                'categories.id',
                'categories.name',
                'categories.description'
            ])
            ->get();

        if (array_key_exists('size', $params)) {

            $response->total  = $this->category::when(array_key_exists('filterName', $params), function ($query) use ($params) {
                    return $query->where('categories.name', 'like', '%' . $params['filterName'] . '%');
                })->count();
            $response->pages = ceil($response->total / $params['size']);
        }
        return $response;
    }
}