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

            if (empty($data['sku'])) {

                $data['sku'] = $this->generateSku();

            }

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
        $category->delete();
    }

    protected function generateSku(): string
    {
        do {

            $sku = 'SKU-' . strtoupper(Str::random(8));

        } while (Category::where('sku', $sku)->exists());

        return $sku;
    }
}
