<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function store(array $data): Category
    {
        return DB::transaction(function () use ($data) {

            $data['slug'] = Str::slug($data['name']);

            return Category::create($data);

        });
    }

    public function update(Category $category, array $data): Category
    {
        return DB::transaction(function () use ($category, $data) {

            $data['slug'] = Str::slug($data['name']);

            $category->update($data);

            return $category;

        });
    }

    public function delete(Category $category): void
    {
        $category->forceDelete();
    }

}
