<?php
namespace App\Contracts\Services;
use App\Models\ProductVariant;
interface SeoServiceInterface {
    public function generateProductSchema(ProductVariant $variant): array;
}