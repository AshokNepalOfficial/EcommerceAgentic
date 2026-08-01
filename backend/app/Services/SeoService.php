<?php
namespace App\Services;
use App\Contracts\Services\SeoServiceInterface;
use App\Models\ProductVariant;
class SeoService implements SeoServiceInterface {
    public function generateProductSchema(ProductVariant $variant): array {
        $images = [$variant->seo_og_image];
        foreach ($variant->images->take(5) as $img) if (!in_array($img->image_url, $images)) $images[] = $img->image_url;
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $variant->seo_title,
            'description' => $variant->seo_description,
            'image' => $images,
            'sku' => $variant->sku,
            'brand' => ['@type' => 'Brand', 'name' => $variant->product->brand->name ?? config('app.name')],
            'offers' => [
                '@type' => 'Offer',
                'url' => $variant->canonical_url ?? url('/product/' . $variant->slug),
                'priceCurrency' => 'USD',
                'price' => number_format($variant->price, 2),
                'availability' => $variant->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
            ]
        ];
    }
}