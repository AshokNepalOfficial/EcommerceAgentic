<?php
namespace App\Repositories;
use App\Contracts\Repositories\ProductVariantRepositoryInterface;
use App\Models\ProductVariant;
class ProductVariantRepository implements ProductVariantRepositoryInterface {
    public function findBySlug(string $slug): ?ProductVariant {
        return ProductVariant::with(['product.brand','product.category','images','attributeValues.attribute'])->where('slug',$slug)->where('status',true)->first();
    }
    public function find(int $id): ProductVariant {
        return ProductVariant::with(['product','images'])->findOrFail($id);
    }
    public function updateStock(int $id, int $quantity): ProductVariant {
        $variant = $this->find($id);
        $variant->stock = $quantity;
        $variant->save();
        return $variant;
    }
}