<?php
namespace App\Services;
use App\Contracts\Services\VariantGeneratorServiceInterface;
use App\DTOs\GenerateVariantDTO;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class VariantGeneratorService implements VariantGeneratorServiceInterface {
    public function generateVariants(GenerateVariantDTO $dto): int {
        $product = Product::findOrFail($dto->productId);
        $attributeGroups = Attribute::with('values')->whereIn('id', $dto->attributeIds)->get();
        $template = $dto->slugTemplate ?? '{product_slug}' . collect($attributeGroups)->reduce(fn($c,$a) => $c . '-{' . $a->slug . '}', '');
        $valueArrays = $attributeGroups->map(fn($a) => $a->values->pluck('id')->toArray())->toArray();
        $combinations = $this->cartesianProduct($valueArrays);
        $count = 0;
        DB::beginTransaction();
        try {
            foreach ($combinations as $combination) {
                $valueModels = AttributeValue::whereIn('id', $combination)->get();
                $slug = $this->buildSlug($product, $valueModels, $template);
                $slug = $this->ensureUniqueSlug($slug);
                $valueNames = $valueModels->pluck('value')->toArray();
                $title = $product->name . ' - ' . implode(' - ', $valueNames);
                $sku = strtoupper($dto->skuPrefix . '-' . implode('-', array_map('Str::slug', $valueNames)));
                $variant = ProductVariant::create(['product_id' => $product->id,'title' => $title,'slug' => $slug,'sku' => $sku,'price' => $dto->basePrice,'stock' => $dto->baseStock,'status' => true]);
                $variant->attributeValues()->attach($combination);
                $count++;
            }
            DB::commit();
            return $count;
        } catch (\Exception $e) { DB::rollBack(); throw $e; }
    }
    private function buildSlug(Product $product, $valueModels, string $template): string {
        $slug = str_replace('{product_slug}', $product->slug, $template);
        foreach ($valueModels as $value) $slug = str_replace('{' . $value->attribute->slug . '}', Str::slug($value->value), $slug);
        $slug = preg_replace('/\{[a-zA-Z0-9_]+\}/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    private function ensureUniqueSlug(string $base): string { $slug = $base; $c = 1; while (ProductVariant::where('slug',$slug)->exists()) $slug = $base . '-' . $c++; return $slug; }
    private function cartesianProduct(array $arrays): array { $result = [[]]; foreach ($arrays as $array) { $temp = []; foreach ($result as $combination) foreach ($array as $value) $temp[] = array_merge($combination, [$value]); $result = $temp; } return $result; }
}