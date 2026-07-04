<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function store(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $data['slug'] = Str::slug($data['name']);

            if (empty($data['sku'])) {

                $data['sku'] = $this->generateSku();

            }

            return Product::create($data);

        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

            $data['slug'] = Str::slug($data['name']);

            $product->update($data);

            return $product;

        });
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    protected function generateSku(): string
    {
        do {

            $sku = 'SKU-' . strtoupper(Str::random(8));

        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
