<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Contracts\Services\VariantGeneratorServiceInterface;
use App\DTOs\GenerateVariantDTO;
use App\Models\Product;
use Illuminate\Http\Request;
class VariantController extends Controller {
    public function __construct(protected VariantGeneratorServiceInterface $generator) {}
    public function generate(Request $request) {
        $request->validate(['product_id' => 'required|exists:products,id','attribute_ids' => 'required|array|min:1','price' => 'required|numeric|min:0','stock' => 'required|integer|min:0','slug_template' => 'nullable|string']);
        $product = Product::findOrFail($request->product_id);
        if ($request->filled('slug_template')) { $product->variant_slug_template = $request->slug_template; $product->save(); }
        $dto = new GenerateVariantDTO($request->product_id, $request->attribute_ids, $request->price, $request->stock, $request->sku_prefix ?? $product->slug, $request->slug_template);
        $count = $this->generator->generateVariants($dto);
        return redirect()->route('admin.products.edit', $product->id)->with('success', "Generated {$count} variants.");
    }
}