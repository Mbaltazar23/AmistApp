<?php

namespace App\Http\Repositories;

use App\Models\Category;

class CategoryRepository
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function add(array $params): void
    {
        $this->category->fill($params);
        $this->category->save();
    }

    public function edit(int $categoryId, array $params): void
    {
        $this->category
            ->findOrFail($categoryId)
            ->fill($params)
            ->save();
    }

    public function findById(int $categoryId): category
    {
        return $this->category::findOrFail($categoryId);
    }

    public function delete($categoryId): void
    {
        $this->category::findOrFail($categoryId)->delete();
    }

}
