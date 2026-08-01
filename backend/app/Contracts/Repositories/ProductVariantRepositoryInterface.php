<?php
namespace App\Contracts\Repositories;
use App\Models\ProductVariant;
interface ProductVariantRepositoryInterface {
    public function findBySlug(string $slug): ?ProductVariant;
    public function find(int $id): ProductVariant;
    public function updateStock(int $id, int $quantity): ProductVariant;
}