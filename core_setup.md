```bash
php artisan make:migration create_brands_table
php artisan make:migration create_categories_table
php artisan make:migration create_attributes_table
php artisan make:migration create_attribute_values_table
php artisan make:migration create_products_table
php artisan make:migration create_product_variants_table
php artisan make:migration create_product_variant_attribute_values_table
php artisan make:migration create_product_images_table
php artisan make:migration create_grouped_product_items_table
php artisan make:migration create_bundle_product_items_table
php artisan make:migration create_settings_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:model Brand
php artisan make:model Category
php artisan make:model Attribute
php artisan make:model AttributeValue
php artisan make:model Product
php artisan make:model ProductVariant
php artisan make:model ProductImage
php artisan make:model Setting
php artisan make:model Order
php artisan make:model OrderItem
```

---

**database/migrations/...create_brands_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('brands'); }
};
```

**database/migrations/...create_categories_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('categories'); }
};
```

**database/migrations/...create_attributes_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['select', 'color_swatch', 'text', 'number'])->default('select');
            $table->boolean('is_visible_on_frontend')->default(true);
            $table->boolean('is_used_in_variation')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attributes'); }
};
```

**database/migrations/...create_attribute_values_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->string('swatch_code')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attribute_values'); }
};
```

**database/migrations/...create_products_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['simple', 'variable', 'grouped', 'bundle'])->default('simple');
            $table->string('variant_slug_template')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
```

**database/migrations/...create_product_variants_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('thumbnail')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('custom_meta')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('product_variants'); }
};
```

**database/migrations/...create_product_variant_attribute_values_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('product_variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained()->onDelete('cascade');
            $table->unique(['product_variant_id', 'attribute_value_id'], 'variant_attribute_unique');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('product_variant_attribute_values'); }
};
```

**database/migrations/...create_product_images_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->string('image_url');
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('product_images'); }
};
```

**database/migrations/...create_grouped_product_items_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('grouped_product_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('child_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('grouped_product_items'); }
};
```

**database/migrations/...create_bundle_product_items_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('bundle_product_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('component_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bundle_product_items'); }
};
```

**database/migrations/...create_settings_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('settings'); }
};
```

**database/migrations/...create_orders_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->json('items_snapshot')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
```

**database/migrations/...create_order_items_table.php**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained();
            $table->foreignId('bundle_product_id')->nullable()->constrained('products');
            $table->string('item_type');
            $table->string('name_snapshot');
            $table->decimal('price_snapshot', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};
```

---

**app/Models/Brand.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Brand extends Model {
    protected $fillable = ['name','slug','logo_url','description','status','meta_title','meta_description','meta_keywords','og_title','og_description','og_image'];
    public function products() { return $this->hasMany(Product::class); }
}
```

**app/Models/Category.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model {
    protected $fillable = ['name','slug','description','parent_id','status','meta_title','meta_description','meta_keywords','og_title','og_description','og_image'];
    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function products() { return $this->hasMany(Product::class); }
}
```

**app/Models/Attribute.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attribute extends Model {
    protected $fillable = ['name','slug','type','is_visible_on_frontend','is_used_in_variation','sort_order'];
    public function values() { return $this->hasMany(AttributeValue::class); }
}
```

**app/Models/AttributeValue.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttributeValue extends Model {
    protected $fillable = ['attribute_id','value','swatch_code','sort_order'];
    public function attribute() { return $this->belongsTo(Attribute::class); }
    public function variants() { return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_values'); }
}
```

**app/Models/Product.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $fillable = ['name','slug','short_description','description','type','variant_slug_template','meta_title','meta_description','meta_keywords','og_title','og_description','og_image','category_id','brand_id','status'];
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function groupedProducts() { return $this->belongsToMany(Product::class, 'grouped_product_items', 'parent_product_id', 'child_product_id')->withPivot('sort_order'); }
    public function bundleComponents() { return $this->belongsToMany(ProductVariant::class, 'bundle_product_items', 'bundle_product_id', 'component_variant_id')->withPivot('quantity'); }
}
```

**app/Models/ProductVariant.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductVariant extends Model {
    protected $fillable = ['product_id','title','slug','description','sku','price','stock','thumbnail','weight','custom_meta','status','meta_title','meta_description','meta_keywords','og_title','og_description','og_image','canonical_url'];
    protected $casts = ['custom_meta' => 'array'];
    public function product() { return $this->belongsTo(Product::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function attributeValues() { return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values'); }
    public function getPrimaryImageAttribute() {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first() ?? (object) ['image_url' => $this->thumbnail, 'alt_text' => $this->title];
    }
    public function getSeoTitleAttribute() { return $this->meta_title ?? $this->product->meta_title ?? $this->title; }
    public function getSeoDescriptionAttribute() { return $this->meta_description ?? $this->product->meta_description ?? $this->description ?? $this->product->short_description; }
    public function getSeoOgImageAttribute() { return $this->og_image ?? $this->product->og_image ?? $this->primary_image->image_url ?? asset('default-og.jpg'); }
}
```

**app/Models/ProductImage.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductImage extends Model {
    protected $fillable = ['product_variant_id','image_url','alt_text','title','is_primary','sort_order'];
    public function variant() { return $this->belongsTo(ProductVariant::class); }
}
```

**app/Models/Setting.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {
    protected $fillable = ['key','value'];
}
```

**app/Models/Order.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $fillable = ['user_id','order_number','subtotal','tax_amount','shipping_cost','grand_total','items_snapshot','status'];
    protected $casts = ['items_snapshot' => 'array'];
    public function items() { return $this->hasMany(OrderItem::class); }
}
```

**app/Models/OrderItem.php**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {
    protected $fillable = ['order_id','product_variant_id','bundle_product_id','item_type','name_snapshot','price_snapshot','quantity'];
}
```

---

**app/Contracts/Repositories/ProductVariantRepositoryInterface.php**
```php
<?php
namespace App\Contracts\Repositories;
use App\Models\ProductVariant;
interface ProductVariantRepositoryInterface {
    public function findBySlug(string $slug): ?ProductVariant;
    public function find(int $id): ProductVariant;
    public function updateStock(int $id, int $quantity): ProductVariant;
}
```

**app/Contracts/Repositories/ProductRepositoryInterface.php**
```php
<?php
namespace App\Contracts\Repositories;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
interface ProductRepositoryInterface {
    public function find(int $id): Product;
    public function getPaginatedForListing(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
```

---

**app/Contracts/Services/CartServiceInterface.php**
```php
<?php
namespace App\Contracts\Services;
use App\DTOs\AddToCartDTO;
interface CartServiceInterface {
    public function addToCart(AddToCartDTO $dto): bool;
    public function getCart(): array;
    public function clearCart(): void;
}
```

**app/Contracts/Services/CheckoutServiceInterface.php**
```php
<?php
namespace App\Contracts\Services;
use App\DTOs\CheckoutDTO;
use App\Models\Order;
interface CheckoutServiceInterface {
    public function processOrder(CheckoutDTO $dto): Order;
}
```

**app/Contracts/Services/VariantGeneratorServiceInterface.php**
```php
<?php
namespace App\Contracts\Services;
use App\DTOs\GenerateVariantDTO;
interface VariantGeneratorServiceInterface {
    public function generateVariants(GenerateVariantDTO $dto): int;
}
```

**app/Contracts/Services/SeoServiceInterface.php**
```php
<?php
namespace App\Contracts\Services;
use App\Models\ProductVariant;
interface SeoServiceInterface {
    public function generateProductSchema(ProductVariant $variant): array;
}
```

---

**app/DTOs/AddToCartDTO.php**
```php
<?php
namespace App\DTOs;
class AddToCartDTO {
    public function __construct(
        public readonly int $productId,
        public readonly ?int $variantId = null,
        public readonly int $quantity = 1
    ) {}
}
```

**app/DTOs/CheckoutDTO.php**
```php
<?php
namespace App\DTOs;
class CheckoutDTO {
    public function __construct(
        public readonly int $userId,
        public readonly array $address,
        public readonly string $paymentMethod,
        public readonly array $cartItems
    ) {}
}
```

**app/DTOs/GenerateVariantDTO.php**
```php
<?php
namespace App\DTOs;
class GenerateVariantDTO {
    public function __construct(
        public readonly int $productId,
        public readonly array $attributeIds,
        public readonly float $basePrice,
        public readonly int $baseStock,
        public readonly string $skuPrefix,
        public readonly ?string $slugTemplate = null
    ) {}
}
```

---

**app/Repositories/ProductVariantRepository.php**
```php
<?php
namespace App\Repositories;
use App\Contracts\Repositories\ProductVariantRepositoryInterface;
use App\Models\ProductVariant;
class ProductVariantRepository implements ProductVariantRepositoryInterface {
    public function findBySlug(string $slug): ?ProductVariant {
        return ProductVariant::with(['product.brand', 'product.category', 'images', 'attributeValues.attribute'])->where('slug', $slug)->where('status', true)->first();
    }
    public function find(int $id): ProductVariant {
        return ProductVariant::with(['product', 'images'])->findOrFail($id);
    }
    public function updateStock(int $id, int $quantity): ProductVariant {
        $variant = $this->find($id);
        $variant->stock = $quantity;
        $variant->save();
        return $variant;
    }
}
```

**app/Repositories/ProductRepository.php**
```php
<?php
namespace App\Repositories;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
class ProductRepository implements ProductRepositoryInterface {
    public function find(int $id): Product {
        return Product::with(['brand', 'category'])->findOrFail($id);
    }
    public function getPaginatedForListing(array $filters = [], int $perPage = 20): LengthAwarePaginator {
        $query = Product::query()->where('status', true)->with(['category', 'brand'])->withAggregate('variants as min_price', 'price', 'min');
        if (!empty($filters['category_id'])) $query->where('category_id', $filters['category_id']);
        if (!empty($filters['brand_id'])) $query->where('brand_id', $filters['brand_id']);
        if (!empty($filters['search'])) $query->where('name', 'LIKE', '%' . $filters['search'] . '%');
        switch ($filters['sort'] ?? 'newest') {
            case 'price_low': $query->orderBy('min_price', 'asc'); break;
            case 'price_high': $query->orderBy('min_price', 'desc'); break;
            default: $query->orderBy('created_at', 'desc');
        }
        return $query->paginate($perPage);
    }
}
```

---

**app/Services/CartService.php**
```php
<?php
namespace App\Services;
use App\Contracts\Services\CartServiceInterface;
use App\DTOs\AddToCartDTO;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;
class CartService implements CartServiceInterface {
    public function addToCart(AddToCartDTO $dto): bool {
        $product = Product::with(['variants', 'bundleComponents'])->findOrFail($dto->productId);
        $cart = Session::get('cart', []);
        switch ($product->type) {
            case 'simple':
                $variant = $product->variants()->firstOrFail();
                $key = 'variant_' . $variant->id;
                if (isset($cart[$key])) $cart[$key]['quantity'] += $dto->quantity;
                else $cart[$key] = ['variant_id' => $variant->id, 'title' => $variant->title, 'price' => $variant->price, 'quantity' => $dto->quantity, 'type' => 'variant'];
                break;
            case 'variable':
                $variant = ProductVariant::findOrFail($dto->variantId);
                $key = 'variant_' . $variant->id;
                if (isset($cart[$key])) $cart[$key]['quantity'] += $dto->quantity;
                else $cart[$key] = ['variant_id' => $variant->id, 'title' => $variant->title, 'price' => $variant->price, 'quantity' => $dto->quantity, 'type' => 'variant'];
                break;
            case 'bundle':
                $key = 'bundle_' . $product->id;
                $price = $product->bundleComponents->sum(fn($c) => $c->price * $c->pivot->quantity);
                if (isset($cart[$key])) $cart[$key]['quantity'] += $dto->quantity;
                else $cart[$key] = ['product_id' => $product->id, 'title' => $product->name . ' (Bundle)', 'price' => $price, 'quantity' => $dto->quantity, 'type' => 'bundle', 'components' => $product->bundleComponents];
                break;
            case 'grouped': throw new \Exception('Add individual items from the group page.');
        }
        Session::put('cart', $cart);
        return true;
    }
    public function getCart(): array { return Session::get('cart', []); }
    public function clearCart(): void { Session::forget('cart'); }
}
```

**app/Services/CheckoutService.php**
```php
<?php
namespace App\Services;
use App\Contracts\Services\CheckoutServiceInterface;
use App\DTOs\CheckoutDTO;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CheckoutService implements CheckoutServiceInterface {
    public function processOrder(CheckoutDTO $dto): Order {
        DB::beginTransaction();
        try {
            $subtotal = collect($dto->cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
            $order = Order::create([
                'user_id' => $dto->userId,
                'order_number' => 'ORD-' . Str::random(10),
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'shipping_cost' => 0,
                'grand_total' => $subtotal,
                'items_snapshot' => $dto->cartItems,
                'status' => 'pending'
            ]);
            foreach ($dto->cartItems as $item) {
                if ($item['type'] === 'bundle') {
                    foreach ($item['components'] as $comp) {
                        $comp->stock -= ($comp->pivot->quantity * $item['quantity']);
                        $comp->save();
                    }
                    OrderItem::create(['order_id' => $order->id, 'bundle_product_id' => $item['product_id'], 'item_type' => 'bundle', 'name_snapshot' => $item['title'], 'price_snapshot' => $item['price'], 'quantity' => $item['quantity']]);
                } else {
                    $variant = ProductVariant::findOrFail($item['variant_id']);
                    $variant->stock -= $item['quantity'];
                    $variant->save();
                    OrderItem::create(['order_id' => $order->id, 'product_variant_id' => $variant->id, 'item_type' => 'variant', 'name_snapshot' => $item['title'], 'price_snapshot' => $item['price'], 'quantity' => $item['quantity']]);
                }
            }
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

**app/Services/VariantGeneratorService.php**
```php
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
        $template = $dto->slugTemplate ?? '{product_slug}' . collect($attributeGroups)->reduce(fn($carry, $attr) => $carry . '-{' . $attr->slug . '}', '');
        $valueArrays = $attributeGroups->map(fn($attr) => $attr->values->pluck('id')->toArray())->toArray();
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
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'title' => $title,
                    'slug' => $slug,
                    'sku' => $sku,
                    'price' => $dto->basePrice,
                    'stock' => $dto->baseStock,
                    'status' => true,
                ]);
                $variant->attributeValues()->attach($combination);
                $count++;
            }
            DB::commit();
            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    private function buildSlug(Product $product, $valueModels, string $template): string {
        $slug = str_replace('{product_slug}', $product->slug, $template);
        foreach ($valueModels as $value) {
            $slug = str_replace('{' . $value->attribute->slug . '}', Str::slug($value->value), $slug);
        }
        $slug = preg_replace('/\{[a-zA-Z0-9_]+\}/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    private function ensureUniqueSlug(string $base): string {
        $slug = $base;
        $counter = 1;
        while (ProductVariant::where('slug', $slug)->exists()) $slug = $base . '-' . $counter++;
        return $slug;
    }
    private function cartesianProduct(array $arrays): array {
        $result = [[]];
        foreach ($arrays as $array) {
            $temp = [];
            foreach ($result as $combination) {
                foreach ($array as $value) {
                    $temp[] = array_merge($combination, [$value]);
                }
            }
            $result = $temp;
        }
        return $result;
    }
}
```

**app/Services/SeoService.php**
```php
<?php
namespace App\Services;
use App\Contracts\Services\SeoServiceInterface;
use App\Models\ProductVariant;
class SeoService implements SeoServiceInterface {
    public function generateProductSchema(ProductVariant $variant): array {
        $images = [$variant->seo_og_image];
        foreach ($variant->images->take(5) as $img) {
            if (!in_array($img->image_url, $images)) $images[] = $img->image_url;
        }
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
```

---

**app/Http/Controllers/ProductController.php**
```php
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
            return view('product.detail', compact('product', 'variant', 'siblings', 'schema'));
        }
        $product = Product::with(['brand', 'category', 'groupedProducts.variants.images', 'bundleComponents.images'])->where('slug', $slug)->where('status', true)->firstOrFail();
        return view('product.detail', compact('product', 'variant' => null, 'siblings' => collect(), 'schema' => null));
    }
}
```

**app/Http/Controllers/CartController.php**
```php
<?php
namespace App\Http\Controllers;
use App\Contracts\Services\CartServiceInterface;
use App\DTOs\AddToCartDTO;
use App\Http\Requests\AddToCartRequest;
class CartController extends Controller {
    public function __construct(protected CartServiceInterface $cartService) {}
    public function add(AddToCartRequest $request) {
        $dto = new AddToCartDTO($request->product_id, $request->variant_id, $request->quantity ?? 1);
        try {
            $this->cartService->addToCart($dto);
            return back()->with('success', 'Item added to cart!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function index() {
        $cartItems = $this->cartService->getCart();
        return view('cart.index', compact('cartItems'));
    }
}
```

**app/Http/Controllers/CheckoutController.php**
```php
<?php
namespace App\Http\Controllers;
use App\Contracts\Services\CartServiceInterface;
use App\Contracts\Services\CheckoutServiceInterface;
use App\DTOs\CheckoutDTO;
use App\Http\Requests\CheckoutRequest;
class CheckoutController extends Controller {
    public function __construct(protected CartServiceInterface $cartService, protected CheckoutServiceInterface $checkoutService) {}
    public function index() {
        $cartItems = $this->cartService->getCart();
        return view('checkout.index', compact('cartItems'));
    }
    public function place(CheckoutRequest $request) {
        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) return back()->with('error', 'Your cart is empty.');
        $dto = new CheckoutDTO(auth()->id() ?? null, $request->address, $request->payment_method, $cartItems);
        try {
            $order = $this->checkoutService->processOrder($dto);
            $this->cartService->clearCart();
            return redirect()->route('order.confirmation', $order->id)->with('success', 'Order placed!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

**app/Http/Controllers/Admin/VariantController.php**
```php
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_ids' => 'required|array|min:1',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'slug_template' => 'nullable|string'
        ]);
        $product = Product::findOrFail($request->product_id);
        if ($request->filled('slug_template')) {
            $product->variant_slug_template = $request->slug_template;
            $product->save();
        }
        $dto = new GenerateVariantDTO(
            $request->product_id,
            $request->attribute_ids,
            $request->price,
            $request->stock,
            $request->sku_prefix ?? $product->slug,
            $request->slug_template
        );
        $count = $this->generator->generateVariants($dto);
        return redirect()->route('admin.products.edit', $product->id)->with('success', "Generated {$count} variants.");
    }
}
```

---

**app/Http/Requests/AddToCartRequest.php**
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class AddToCartRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return ['product_id' => 'required|exists:products,id', 'variant_id' => 'nullable|exists:product_variants,id', 'quantity' => 'integer|min:1'];
    }
}
```

**app/Http/Requests/CheckoutRequest.php**
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CheckoutRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return ['address' => 'required|array', 'payment_method' => 'required|string'];
    }
}
```

---

**app/Providers/RepositoryServiceProvider.php**
```php
<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ProductVariantRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\CartServiceInterface;
use App\Contracts\Services\CheckoutServiceInterface;
use App\Contracts\Services\VariantGeneratorServiceInterface;
use App\Contracts\Services\SeoServiceInterface;
use App\Repositories\ProductVariantRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\VariantGeneratorService;
use App\Services\SeoService;
class RepositoryServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(ProductVariantRepositoryInterface::class, ProductVariantRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);
        $this->app->bind(VariantGeneratorServiceInterface::class, VariantGeneratorService::class);
        $this->app->bind(SeoServiceInterface::class, SeoService::class);
    }
    public function boot(): void {}
}
```

---

**routes/web.php**
```php
<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\VariantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
});

Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/place', [CheckoutController::class, 'place'])->name('checkout.place');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/variants/generate', [VariantController::class, 'generate'])->name('variants.generate');
});
```