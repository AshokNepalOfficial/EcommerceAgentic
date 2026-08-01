<?php
namespace App\Http\Controllers;
use App\Contracts\Repositories\ProductVariantRepositoryInterface;
use App\Contracts\Services\SeoServiceInterface;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller {
    public function __construct(protected ProductVariantRepositoryInterface $variantRepo, protected SeoServiceInterface $seoService) {}
    public function show($slug) {
        $variant = $this->variantRepo->findBySlug($slug);
        if ($variant) {
            $product = $variant->product;
            $siblings = $product->variants()->where('id', '!=', $variant->id)->where('status', true)->get();
            $schema = $this->seoService->generateProductSchema($variant);
            return view('product.detail', compact('product','variant','siblings','schema'));
        }
        $product = Product::with(['brand','category','groupedProducts.variants.images','bundleComponents.images'])->where('slug',$slug)->where('status',true)->firstOrFail();
        return view('product.detail', [
            'product' => $product,
            'variant' => null,
            'siblings' => collect(),
            'schema' => null
        ]);
    }
}